const saveBtn = document.getElementById("save-button");
const postForm = document.getElementById("post-form");

const imageUploadHandler = (blobInfo, progress) =>
    new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open("POST", uploadURL);
        xhr.setRequestHeader("X-CSRF-Token", csrfToken);

        xhr.upload.onprogress = (e) => {
            progress((e.loaded / e.total) * 100);
        };

        xhr.onload = () => {
            if (xhr.status === 403) {
                reject({ message: "HTTP Error: " + xhr.status, remove: true });
                return;
            }

            if (xhr.status < 200 || xhr.status >= 300) {
                reject("HTTP Error: " + xhr.status);
                return;
            }

            const json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != "string") {
                reject("Invalid JSON: " + xhr.responseText);
                return;
            }

            resolve(json.location);
        };

        xhr.onerror = (error) => {
            console.log(error);
            reject(
                "Image upload failed due to a XHR Transport error. Code: " +
                    xhr.status
            );
        };

        const formData = new FormData();
        formData.append("image", blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
    });

tinymce.init({
    selector: "#editor",
    plugins: "image code",
    toolbar: [
        "undo redo | styles | bold italic | alignleft aligncenter alignright | link image ",
    ],

    // image handler
    image_title: true,
    images_upload_handler: imageUploadHandler,
    automatic_uploads: false,
    images_upload_url: "",
    images_reuse_filename: true,

    // file picker
    file_picker_types: "image",
    file_picker_callback: (cb, value, meta) => {
        const input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("accept", "image/*");

        input.addEventListener("change", (e) => {
            const file = e.target.files[0];

            if (!file.type.startsWith("image/")) {
                alert("Please select an image file!");
                return;
            }

            const reader = new FileReader();
            reader.addEventListener("load", () => {
                /*
                Note: Now we need to register the blob in TinyMCEs image blob
                registry. In the next release this part hopefully won't be
                necessary, as we are looking to handle it internally.
                */
                const id = "blobid" + new Date().getTime();
                const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                const base64 = reader.result.split(",")[1];
                const blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            });
            reader.readAsDataURL(file);
        });

        input.click();
    },
    content_style:
        "body { font-family:Helvetica,Arial,sans-serif; font-size:16px }",
});

saveBtn.addEventListener("click", function (e) {
    e.preventDefault();
    tinymce.activeEditor.uploadImages().then(() => {
        postForm.submit();
    });
});
