import os

OUTPUT_FILE = "infofolder.txt"
EXCLUDED_NAMES = {".git", ".gitattributes", "README.md", OUTPUT_FILE}

def generate_tree(start_path, output_file):
    start_path = os.path.abspath(start_path)

    with open(output_file, "w", encoding="utf-8") as f:
        for root, dirs, files in os.walk(start_path):
            dirs[:] = sorted(d for d in dirs if d not in EXCLUDED_NAMES)
            files = sorted(file_name for file_name in files if file_name not in EXCLUDED_NAMES)

            rel_root = os.path.relpath(root, start_path)
            if rel_root == ".":
                rel_root = ""

            level = 0 if not rel_root else rel_root.count(os.sep) + 1
            indent = "    " * level

            folder_name = os.path.basename(root) if rel_root else os.path.basename(start_path)
            f.write(f"{indent}[DIR] {folder_name}\n")

            sub_indent = "    " * (level + 1)

            for file_name in files:
                relative_file_path = os.path.relpath(os.path.join(root, file_name), start_path)
                f.write(f"{sub_indent}[FILE] {relative_file_path}\n")

if __name__ == "__main__":
    current_folder = os.getcwd()
    output_path = os.path.join(current_folder, OUTPUT_FILE)
    generate_tree(current_folder, output_path)
    print(f"Fisier generat: {output_path}")