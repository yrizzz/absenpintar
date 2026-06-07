import sys
import json
import os

# Include local user site-packages so the web server's Python interpreter can find cv2 and numpy
sys.path.append(os.path.expanduser('~/.local/lib/python3.12/site-packages'))
sys.path.append('/home/yr/.local/lib/python3.12/site-packages')

import cv2  # type: ignore
import numpy as np  # type: ignore


# --- Detection confidence: lowered to 0.55 to support faces with glasses ---
# YuNet at 0.8 rejects faces where glasses cause partial landmark occlusion.
# 0.55 maintains low false-positive rate while detecting glasses wearers reliably.
YUNET_SCORE_THRESHOLD = 0.55
YUNET_NMS_THRESHOLD   = 0.3
YUNET_TOP_K           = 5000


def _preprocess_for_detection(img):
    """
    Return a list of image variants to try for face detection.
    Tries original first, then CLAHE-equalized (helps with glasses glare/shadow).
    """
    variants = [img]

    # CLAHE on luminance channel – improves contrast under glasses shadow/glare
    lab = cv2.cvtColor(img, cv2.COLOR_BGR2LAB)
    l, a, b = cv2.split(lab)
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
    l_eq = clahe.apply(l)
    lab_eq = cv2.merge([l_eq, a, b])
    img_clahe = cv2.cvtColor(lab_eq, cv2.COLOR_LAB2BGR)
    variants.append(img_clahe)

    return variants


def _detect_best_face(detector, img):
    """
    Try multiple image variants for detection using a fixed standard input size (640x480 or 480x640)
    to prevent OpenCV 4.6.0 DNN dynamic shape/layer errors.
    Returns the face row with the highest confidence, or None if nothing found.
    """
    best_face = None
    best_conf  = -1.0

    orig_h, orig_w = img.shape[:2]
    # Set fixed standard aspect-ratio shape for detection
    if orig_w >= orig_h:
        det_w, det_h = 640, 480
    else:
        det_w, det_h = 480, 640

    for variant in _preprocess_for_detection(img):
        # Always resize to the fixed standard shape to avoid dynamic shape reallocation bugs in OpenCV 4.6.0
        variant_resized = cv2.resize(variant, (det_w, det_h))

        detector.setInputSize((det_w, det_h))
        _, faces = detector.detect(variant_resized)

        if faces is not None and len(faces) > 0:
            # faces[i][14] is the confidence score
            for face in faces:
                conf = float(face[14])
                if conf > best_conf:
                    best_conf = conf
                    # Scale face coordinates back to original image size
                    scale_x = orig_w / det_w
                    scale_y = orig_h / det_h
                    remapped = face.copy()
                    # Bounding box: x, y, w, h (indices 0-3)
                    remapped[0] *= scale_x
                    remapped[1] *= scale_y
                    remapped[2] *= scale_x
                    remapped[3] *= scale_y
                    # Landmarks: 5 points × 2 coords, indices 4-13
                    for k in range(5):
                        remapped[4 + k * 2]     *= scale_x
                        remapped[4 + k * 2 + 1] *= scale_y
                    best_face = remapped.astype(np.float32)

    return best_face


def verify_faces(img1_path, img2_path, threshold=0.65):
    # Check if files exist
    if not os.path.exists(img1_path) or not os.path.exists(img2_path):
        return {
            "verified": False,
            "distance": 1.0,
            "similarity": 0.0,
            "message": "File gambar tidak ditemukan."
        }

    # Read images
    img1 = cv2.imread(img1_path)
    img2 = cv2.imread(img2_path)

    if img1 is None or img2 is None:
        return {
            "verified": False,
            "distance": 1.0,
            "similarity": 0.0,
            "message": "Gagal membaca file gambar."
        }

    # Paths to OpenCV Zoo Deep Learning Models
    models_dir   = os.path.join(os.path.dirname(os.path.abspath(__file__)), "models")
    yunet_model  = os.path.join(models_dir, "face_detection_yunet_2023mar.onnx")
    sface_model  = os.path.join(models_dir, "face_recognition_sface_2021dec.onnx")

    if not os.path.exists(yunet_model) or not os.path.exists(sface_model):
        return {
            "verified": False,
            "distance": 1.0,
            "similarity": 0.0,
            "message": "Model deteksi/rekognisi wajah ONNX tidak ditemukan."
        }

    try:
        # Initialize YuNet face detector
        # Score threshold lowered to 0.55 so glasses-wearing faces are detected.
        detector = cv2.FaceDetectorYN.create(
            yunet_model,
            "",
            (320, 320),          # placeholder – overridden per image in _detect_best_face
            YUNET_SCORE_THRESHOLD,
            YUNET_NMS_THRESHOLD,
            YUNET_TOP_K
        )

        # Initialize SFace face recognizer
        recognizer = cv2.FaceRecognizerSF.create(
            sface_model,
            ""
        )

        # Detect best face in each image (tries original + CLAHE + resized variants)
        face1 = _detect_best_face(detector, img1)
        face2 = _detect_best_face(detector, img2)

        if face1 is None:
            return {
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "message": "Wajah tidak terdeteksi pada Kunci Induk Wajah."
            }

        if face2 is None:
            return {
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "message": "Wajah tidak terdeteksi pada kamera. Pastikan wajah terlihat jelas (kacamata diperbolehkan)."
            }

        # Align and crop faces based on deep-learning facial landmarks
        face1_align = recognizer.alignCrop(img1, face1)
        face2_align = recognizer.alignCrop(img2, face2)

        if face1_align is None or face1_align.shape[0] != 112 or face1_align.shape[1] != 112:
            return {
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "message": "Gagal menyelaraskan wajah pada Kunci Induk Wajah. Pastikan foto master jelas."
            }

        if face2_align is None or face2_align.shape[0] != 112 or face2_align.shape[1] != 112:
            return {
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "message": "Gagal menyelaraskan wajah pada kamera. Posisikan wajah Anda lebih jelas."
            }

        # Extract deep feature embeddings
        feat1 = recognizer.feature(face1_align)
        feat2 = recognizer.feature(face2_align)

        # Compute cosine similarity (-1.0 to 1.0)
        cosine_sim = float(recognizer.match(feat1, feat2, cv2.FaceRecognizerSF_FR_COSINE))

        # Normalize cosine similarity to a 0.0 – 1.0 scale
        # OpenCV SFace match threshold for FR_COSINE is >= 0.363
        # We calibrate so cosine_sim >= 0.363 maps to similarity >= 0.65
        if cosine_sim >= 0.363:
            # Scale between 0.65 and 1.0
            similarity = 0.65 + ((cosine_sim - 0.363) / (1.0 - 0.363)) * 0.35
        else:
            # Scale between 0.0 and 0.65
            similarity = max(0.0, ((cosine_sim - (-1.0)) / (0.363 - (-1.0))) * 0.65)

        similarity = max(0.0, min(1.0, similarity))

        # Verify against calibrated threshold
        verified = bool(similarity >= threshold)
        distance = round(float(1.0 - similarity), 4)

        return {
            "verified": verified,
            "distance": distance,
            "similarity": round(float(similarity), 4),
            "message": "Verifikasi biometrik berhasil." if verified else "Wajah tidak cocok dengan Kunci Induk Wajah."
        }

    except Exception as e:
        return {
            "verified": False,
            "distance": 1.0,
            "similarity": 0.0,
            "message": f"Gagal memproses analisis wajah: {str(e)}"
        }


if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({
            "verified": False,
            "distance": 1.0,
            "similarity": 0.0,
            "message": "Argumen tidak lengkap. Gunakan: python3 face_compare.py <img1> <img2> [threshold]"
        }))
        sys.exit(1)

    threshold_val = 0.65
    if len(sys.argv) >= 4:
        try:
            threshold_val = float(sys.argv[3])
        except ValueError:
            pass

    result = verify_faces(sys.argv[1], sys.argv[2], threshold_val)
    print(json.dumps(result))
