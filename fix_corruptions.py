import os
import re

def fix_file(path):
    try:
        with open(path, 'rb') as f:
            content = f.read()
        
        # Try to decode as UTF-8, then fall back to latin-1
        try:
            text = content.decode('utf-8')
        except UnicodeDecodeError:
            text = content.decode('latin-1')
        
        # Patterns to fix
        # Corrupted decorative boxes: " ? ? ?" -> "════════"
        # Since I can't easily guess the exact length, I'll just replace sequences of bad chars
        text = re.sub(r'\s?[?]\s?', '═', text)
        
        # Corrupted ""~." or "~." -> "★"
        text = re.sub(r'[~.]', '★', text)
        
        # Corrupted ""?" -> "·" or "—" or "é" depending on context
        # This is harder, but let's try common ones
        text = text.replace('Ǹ', 'é')
        text = text.replace('Ǧ', 'ê')
        text = text.replace('Ǿ', 'î')
        text = text.replace('ǽ', 'â')
        text = text.replace('ǽ', 'à') # ambiguous
        
        # Fix the "DT" that were wrongly injected into text
        # This is dangerous because some DT might be intentional (currency)
        # But "DT" followed by "? " or similar is likely corruption
        # In the bio: "post-partum DT" " -> "post-partum —"
        text = re.sub(r'DT\s?[?]\s?', ' — ', text)
        text = re.sub(r'DT\s?', ' — ', text)
        
        # Fix emojis if they are garbled
        # Example: "Y~DT?TDT?" -> "🧘‍♀️"
        # This is too specific, but let's try to just remove the junk if we can't fix it
        
        # Final cleanup of remaining REPLACEMENT CHARACTERs
        text = text.replace('', '')
        
        with open(path, 'w', encoding='utf-8') as f:
            f.write(text)
        print(f"Fixed {path}")
    except Exception as e:
        print(f"Error fixing {path}: {e}")

files_to_fix = [
    'index.php',
    'pages/coachs.php',
    'pages/dashboard.php',
    'pages/login.php',
    'pages/register.php',
    'php/login.php',
    'php/register.php',
    'php/reservations.php',
    'js/app.js',
    'css/style.css'
]

for f in files_to_fix:
    if os.path.exists(f):
        fix_file(f)
