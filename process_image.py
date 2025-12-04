from PIL import Image, ImageEnhance
import os

source_path = r"C:/Users/rangg/.gemini/antigravity/brain/2d3ffb96-895f-4ee4-af80-0b384ca98d05/uploaded_image_1764833520919.png"
dest_path = r"c:/xampp/htdocs/evoting/asset/images/batik_bg.png"

try:
    img = Image.open(source_path)
    
    # Convert to grayscale
    img = img.convert('L')
    
    # Increase brightness to make it "putih abu abu" (light gray)
    enhancer = ImageEnhance.Brightness(img)
    img = enhancer.enhance(1.5) # Increase brightness by 50%
    
    # Reduce contrast to make it subtle
    enhancer = ImageEnhance.Contrast(img)
    img = enhancer.enhance(0.5) # Reduce contrast by 50%
    
    img.save(dest_path)
    print(f"Successfully processed image to {dest_path}")
except Exception as e:
    print(f"Error processing image: {e}")
