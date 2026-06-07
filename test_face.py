import sys
import os
import traceback

# Include local user site-packages
sys.path.append(os.path.expanduser('~/.local/lib/python3.12/site-packages'))
sys.path.append('/home/yr/.local/lib/python3.12/site-packages')

import cv2
import numpy as np

def test_pipeline(img_path):
    print(f"1. Reading image: {img_path}")
    img = cv2.imread(img_path)
    if img is None:
        print("❌ Failed to read image.")
        return
    print(f"✅ Image loaded with shape: {img.shape}")

    models_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)), "models")
    yunet_model = os.path.join(models_dir, "face_detection_yunet_2022mar.onnx")
    sface_model = os.path.join(models_dir, "face_recognition_sface_2021dec.onnx")

    print(f"2. Model paths:\n - YuNet: {yunet_model} (Exists: {os.path.exists(yunet_model)})\n - SFace: {sface_model} (Exists: {os.path.exists(sface_model)})")

    try:
        print("3. Creating YuNet detector...")
        detector = cv2.FaceDetectorYN.create(
            yunet_model,
            "",
            (320, 320),
            0.55,
            0.3,
            5000
        )
        print("✅ YuNet detector created.")
    except Exception as e:
        print(f"❌ Failed to create YuNet detector: {e}")
        traceback.print_exc()
        return

    try:
        print("4. Creating SFace recognizer...")
        recognizer = cv2.FaceRecognizerSF.create(
            sface_model,
            ""
        )
        print("✅ SFace recognizer created.")
    except Exception as e:
        print(f"❌ Failed to create SFace recognizer: {e}")
        traceback.print_exc()
        return

    # Let's see what happens during detection
    try:
        print("5. Running YuNet detection on 640x480...")
        det_w, det_h = 640, 480
        if img.shape[1] < img.shape[0]:
            det_w, det_h = 480, 640
            
        variant_resized = cv2.resize(img, (det_w, det_h))
        detector.setInputSize((det_w, det_h))
        print(f" - Set input size to: ({det_w}, {det_h})")
        
        _, faces = detector.detect(variant_resized)
        print(f"✅ YuNet detection completed. Faces found: {len(faces) if faces is not None else 0}")
        if faces is None or len(faces) == 0:
            print("⚠️ No face found in image.")
            return
    except Exception as e:
        print(f"❌ Failed during YuNet detection: {e}")
        traceback.print_exc()
        return

    # Let's align and crop
    try:
        print("6. Aligning and cropping face...")
        face = faces[0]
        # Let's print type of face elements
        print(f" - Face array shape: {face.shape}, dtype: {face.dtype}")
        
        face_align = recognizer.alignCrop(variant_resized, face)
        print(f"✅ Face aligned. Crop shape: {face_align.shape if face_align is not None else None}")
        if face_align is None or face_align.shape[0] != 112 or face_align.shape[1] != 112:
            print("❌ Aligned face shape is not 112x112!")
            return
    except Exception as e:
        print(f"❌ Failed during face alignment: {e}")
        traceback.print_exc()
        return

    # Let's extract features
    try:
        print("7. Extracting features using SFace...")
        feat = recognizer.feature(face_align)
        print(f"✅ Features extracted successfully. Shape: {feat.shape if feat is not None else None}")
    except Exception as e:
        print(f"❌ Failed during feature extraction: {e}")
        traceback.print_exc()
        return

    print("🎉 All pipeline stages passed successfully!")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python3 test_face.py <img_path>")
        sys.exit(1)
    test_pipeline(sys.argv[1])
