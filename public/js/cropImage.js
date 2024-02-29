window.addEventListener("DOMContentLoaded", function () {
    const inputImg = document.getElementById("profile");
    const imgContainer = document.getElementById("imageContainer");
    const imgPreview = document.getElementById("preview");
    const cropBtn = document.getElementById("crop");
    const $modal = $("#cropModal");
    const username = inputImg.getAttribute("data-username");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    let cropper;
    let canvas;

    inputImg.addEventListener("change", function (e) {
        const file = e.target.files[0];

        if (file && file.type.startsWith("image/")) {
            function handleImage(url) {
                imgContainer.src = url;
                $modal.modal("show");
            }
            var reader;

            if (URL) {
                handleImage(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    handleImage(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        } else {
            alert("Please select an image file.");
            inputImg.value = "";
        }
    });

    $modal
        .on("shown.bs.modal", function () {
            if (!cropper) {
                cropper = new Cropper(imgContainer, {
                    aspectRatio: 1,
                    viewMode: 3,
                });
            }
        })
        .on("hidden.bs.modal", function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

    cropBtn.onclick = function () {
        $modal.modal("hide");
        if (cropper) {
            canvas = cropper.getCroppedCanvas({
                width: 150,
                height: 150,
            });
            canvas.toBlob(function (blob) {
                const formData = new FormData();
                formData.append(
                    "profile",
                    blob,
                    `user-profile-cropped.${
                        inputImg.files[0].type.split("/")[1]
                    }`
                );
                fetch(`/@${username}/profile/picture`, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                })
                    .then((response) => {
                        if (response.status === 200) {
                            imgPreview.src = canvas.toDataURL();
                            alert("Profile picture successfully updated.");
                        }
                    })
                    .catch((error) => {
                        if (response.status !== 200) {
                            alert(error);
                        }
                    });
            });
        }
    };
});
