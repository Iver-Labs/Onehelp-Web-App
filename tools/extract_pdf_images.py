#!/usr/bin/env python3
import sys
import os
import fitz  # PyMuPDF

def save_images_and_pages(pdf_path, out_dir, dpi=150):
    doc = fitz.open(pdf_path)
    os.makedirs(out_dir, exist_ok=True)
    total_imgs = 0
    for pno in range(len(doc)):
        page = doc[pno]
        # Render page to PNG
        pix = page.get_pixmap(dpi=dpi)
        page_fname = os.path.join(out_dir, f"page-{pno+1}.png")
        pix.save(page_fname)
        pix = None
        # Extract images
        imgs = page.get_images(full=True)
        if imgs:
            img_index = 0
            for img in imgs:
                xref = img[0]
                base_image = fitz.Pixmap(doc, xref)
                # Convert CMYK or other to RGB if needed
                if base_image.n >= 5:
                    base_image = fitz.Pixmap(fitz.csRGB, base_image)
                ext = "png"
                img_index += 1
                total_imgs += 1
                img_fname = os.path.join(out_dir, f"page-{pno+1}-img-{img_index}.{ext}")
                base_image.save(img_fname)
                base_image = None
    return len(doc), total_imgs

if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("Usage: extract_pdf_images.py <pdf-path> <out-dir>")
        sys.exit(2)
    pdf_path = sys.argv[1]
    out_dir = sys.argv[2]
    pages, imgs = save_images_and_pages(pdf_path, out_dir)
    print(f"Done: pages={pages} images_extracted={imgs}")
