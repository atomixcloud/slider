#!/usr/bin/env python3

import os
import http.server
import socketserver
import json
import sys
import webbrowser

IMAGE_EXTENSIONS = {".jpg", ".jpeg", ".png", ".gif", ".bmp", ".webp"}
HTML_DIR = os.path.expanduser("~/.slider")  # Fixed location for UI files


def scan_images(directory):
    """Scan the given directory for images."""
    images = [f for f in os.listdir(directory) if os.path.splitext(f)[1].lower() in IMAGE_EXTENSIONS]
    return images


class ImageRequestHandler(http.server.SimpleHTTPRequestHandler):
    def translate_path(self, path):
        if path == "/":
            path = "/index.html"
        elif path.startswith("/") and any(path.endswith(ext) for ext in IMAGE_EXTENSIONS):
            return os.path.join(os.getcwd(), path.lstrip("/"))  # Serve images from the current working directory
        return os.path.join(HTML_DIR, path.lstrip("/"))

    def do_GET(self):
        if self.path == "/images.json":
            images = scan_images(os.getcwd())
            self.send_response(200)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(json.dumps(images).encode())
        else:
            super().do_GET()


def start_server():
    """Start a simple HTTP server to serve the slideshow."""
    handler = ImageRequestHandler
    with socketserver.TCPServer(("", 0), handler) as httpd:  # Bind to a free port
        port = httpd.server_address[1]  # Get the assigned port
        print(f"Serving at http://localhost:{port}")
        webbrowser.open(f"http://localhost:{port}/index.html")
        httpd.serve_forever()


if __name__ == "__main__":
    start_server()
