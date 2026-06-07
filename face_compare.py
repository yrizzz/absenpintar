import sys
import json
import os

# Include local user site-packages so the web server's Python interpreter can find cv2 and numpy
sys.path.append(os.path.expanduser('~/.local/lib/python3.12/site-packages'))
sys.path.append('/home/yr/.local/lib/python3.12/site-packages')

import cv2 # type: ignore
import numpy as np # type: ignore

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
    models_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)), "models")
    yunet_model = os.path.join(models_dir, "face_detection_yunet_2023mar.onnx")
    sface_model = os.path.join(models_dir, "face_recognition_sface_2021dec.onnx")

    if not os.path.exists(yunet_model) or not os.path.exists(sface_model):
        return {
            "verified": False,
            "distance": 1.0,
            "similarity": 0.0,
            "message": "Model deteksi/rekognisi wajah ONNX tidak ditemukan."
        }

    try:
        # Initialize YuNet face detector
        detector = cv2.FaceDetectorYN.create(
            yunet_model,
            "",
            (320, 320),
            0.8,
            0.3,
            5000
        )

        # Initialize SFace face recognizer
        recognizer = cv2.FaceRecognizerSF.create(
            sface_model,
            ""
        )

        # Detect face on Image 1 (Master)
        h1, w1, _ = img1.shape
        detector.setInputSize((w1, h1))
        _, faces1 = detector.detect(img1)

        # Detect face on Image 2 (Selfie)
        h2, w2, _ = img2.shape
        detector.setInputSize((w2, h2))
        _, faces2 = detector.detect(img2)

        if faces1 is None or len(faces1) == 0:
            return {
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "message": "Wajah tidak terdeteksi pada Kunci Induk Wajah."
            }

        if faces2 is None or len(faces2) == 0:
            return {
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "message": "Wajah tidak terdeteksi pada kamera. Posisikan wajah Anda dengan jelas."
            }

        # Align and crop faces based on deep-learning facial landmarks
        face1_align = recognizer.alignCrop(img1, faces1[0])
        face2_align = recognizer.alignCrop(img2, faces2[0])

        # Extract deep feature embeddings
        feat1 = recognizer.feature(face1_align)
        feat2 = recognizer.feature(face2_align)

        # Compute cosine similarity (-1.0 to 1.0)
        cosine_sim = float(recognizer.match(feat1, feat2, cv2.FaceRecognizerSF_FR_COSINE))

        # Normalize cosine similarity to a beautiful 0.0 - 1.0 scale
        # OpenCV SFace match threshold for FR_COSINE is >= 0.363
        # We calibrate this so that a cosine_sim >= 0.363 maps to a similarity >= threshold
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
