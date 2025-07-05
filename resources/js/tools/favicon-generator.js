export default function imageUploader() {
    return {
        isDragging: false,
        image: null,

        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            this.processFile(file);
        },

        handleInputChange(event) {
            const file = event.target.files[0];
            this.processFile(file);
        },

        processFile(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.image = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                alert('Please upload an image file.');
            }
        },

        removeImage() {
            this.image = null;
            this.$refs.fileInput.value = ''; // Reset input value
        }
    };
}
