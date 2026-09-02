"""Remove dealership text from bright front license plates in vehicle covers."""

from __future__ import annotations

import argparse
from pathlib import Path

import cv2
import numpy as np


def plate_candidates(image: np.ndarray) -> list[tuple[int, int, int, int]]:
    h, w = image.shape[:2]
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    # White/silver showroom plates; text creates darker holes inside the contour.
    mask = cv2.inRange(gray, 158, 255)
    mask[: int(h * 0.38), :] = 0
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (max(9, w // 70), max(3, h // 280)))
    mask = cv2.morphologyEx(mask, cv2.MORPH_CLOSE, kernel, iterations=2)

    found: list[tuple[int, int, int, int]] = []
    for contour in cv2.findContours(mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)[0]:
        x, y, cw, ch = cv2.boundingRect(contour)
        area = cw * ch
        ratio = cw / max(ch, 1)
        if not (2.1 <= ratio <= 8.5):
            continue
        if not (w * h * 0.00028 <= area <= w * h * 0.018):
            continue
        if cw < w * 0.055 or ch < h * 0.009:
            continue
        # Plates are near the horizontal center and below the bonnet/grille.
        cx = x + cw / 2
        if not (w * 0.18 <= cx <= w * 0.82 and y >= h * 0.43):
            continue
        roi = gray[y : y + ch, x : x + cw]
        dark_fraction = float(np.mean(roi < 115))
        if 0.025 <= dark_fraction <= 0.68:
            found.append((x, y, cw, ch))

    found.sort(key=lambda b: (b[2] * b[3], b[1]), reverse=True)
    return found[:1]


def clean_plate(image: np.ndarray, box: tuple[int, int, int, int]) -> np.ndarray:
    x, y, w, h = box
    out = image.copy()
    # Preserve the physical plate edge; cover only the dealer-print area.
    px = max(2, round(w * 0.07))
    py = max(1, round(h * 0.18))
    x1, y1, x2, y2 = x + px, y + py, x + w - px, y + h - py
    plate = image[y : y + h, x : x + w]
    bright = plate[np.mean(plate, axis=2) > 145]
    color = np.median(bright, axis=0) if len(bright) else np.median(plate.reshape(-1, 3), axis=0)
    overlay = out.copy()
    cv2.rectangle(overlay, (x1, y1), (x2, y2), tuple(int(v) for v in color), -1)
    feather = max(3, (min(w, h) // 5) | 1)
    alpha = np.zeros(out.shape[:2], np.uint8)
    cv2.rectangle(alpha, (x1, y1), (x2, y2), 255, -1)
    alpha = cv2.GaussianBlur(alpha, (feather, feather), 0).astype(np.float32) / 255.0
    return (overlay * alpha[..., None] + out * (1 - alpha[..., None])).astype(np.uint8)


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("source", type=Path)
    parser.add_argument("--output", type=Path)
    parser.add_argument("--mark", action="store_true", help="draw detection boxes instead of cleaning")
    parser.add_argument("--ids", help="comma-separated vehicle ids to process")
    args = parser.parse_args()
    output = args.output or args.source
    output.mkdir(parents=True, exist_ok=True)

    selected = set(args.ids.split(",")) if args.ids else None
    # One unusually low, perspective-skewed plate merges with the white floor.
    overrides = {"498": (153, 750, 100, 28)}
    hits = 0
    for path in sorted(args.source.glob("*.webp")):
        if not path.stem.isdigit():
            continue
        if selected is not None and path.stem not in selected:
            continue
        image = cv2.imread(str(path))
        boxes = [overrides[path.stem]] if path.stem in overrides else plate_candidates(image)
        result = image
        if boxes:
            hits += 1
            if args.mark:
                result = image.copy()
                for x, y, w, h in boxes:
                    cv2.rectangle(result, (x, y), (x + w, y + h), (0, 0, 255), max(2, image.shape[1] // 400))
            else:
                result = clean_plate(image, boxes[0])
        cv2.imwrite(str(output / path.name), result, [cv2.IMWRITE_WEBP_QUALITY, 88])
    print(f"processed={hits}")


if __name__ == "__main__":
    main()
