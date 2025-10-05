document.addEventListener("DOMContentLoaded", () => {
  const BASEURL = "http://localhost/sinergi";

  function showPasswordToggle() {
    const showPasswordButton = document.getElementById("show-password");
    const passwordInput = document.getElementById("password");
    const showIcon = document.getElementById("show-icon");
    const hideIcon = document.getElementById("hide-icon");

    if (!showPasswordButton || !passwordInput || !showIcon || !hideIcon) return;

    showPasswordButton.addEventListener("click", () => {
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        showIcon.classList.add("hidden");
        hideIcon.classList.remove("hidden");
      } else {
        passwordInput.type = "password";
        showIcon.classList.remove("hidden");
        hideIcon.classList.add("hidden");
      }
    });
  }

  function avatarUpload() {
    const addPhotoBtn = document.getElementById("add-photo");
    const fileInput = document.getElementById("file-input");
    const photoContainer = document.getElementById("photo-container");

    if (!addPhotoBtn || !fileInput || !photoContainer) return;

    addPhotoBtn.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", (event) => {
      const file = event.target.files?.[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          photoContainer.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  function sidebarToggle() {
    const toggleButton = document.getElementById("toggleForumsBtn");
    const forumsSidebar = document.getElementById("forumsListSidebar");

    if (!toggleButton || !forumsSidebar) return;

    toggleButton.addEventListener("click", () => {
      forumsSidebar.classList.toggle("-translate-x-full");
    });
  }

  function openModal() {
    const openModalBtn = document.getElementById("AddForum");
    const closeModalBtn = document.getElementById("close-modal-forum");
    const modal = document.getElementById("create-forum-modal");
    const isPrivateCheckbox = document.getElementById("isPrivate");
    const keyForumContainer = document.getElementById("keyForumContainer");
    const form = document.getElementById("create-forum-form");
    const photoContainer = document.getElementById("photo-container");

    if (
      !openModalBtn ||
      !closeModalBtn ||
      !modal ||
      !isPrivateCheckbox ||
      !keyForumContainer
    )
      return;

    const openModal = () => {
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    };
    const closeModal = () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
      form.reset();
      keyForumContainer.classList.add("hidden");
      photoContainer.src = `${BASEURL}/src/asset/image/default.png`;
    };

    openModalBtn.addEventListener("click", openModal);
    closeModalBtn.addEventListener("click", closeModal);

    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });

    isPrivateCheckbox.addEventListener("change", () => {
      keyForumContainer.classList.toggle("hidden", !isPrivateCheckbox.checked);
    });
  }


  function createForum() {
    const form = document.getElementById("create-forum-form");

    if (!form) {
      return;
    }

    const submitButton = document.getElementById("createForm");
    const errorMessageDiv = document.getElementById("modal-error-message");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const originalButtonText = submitButton.innerHTML;
      submitButton.disabled = true;
      submitButton.innerHTML = "Creating...";
      errorMessageDiv.classList.add("hidden");

      const formData = new FormData(form);
      const actionUrl = form.getAttribute("action");

      try {
        const response = await fetch(actionUrl, {
          method: "POST",
          body: formData,
        });
        const result = await response.json();

        if (result.success) {
          window.location.href = result.redirectUrl;
        } else {
          errorMessageDiv.textContent = result.message;
          errorMessageDiv.classList.remove("hidden");
        }
      } catch (error) {
        errorMessageDiv.textContent =
          "Terjadi kesalahan jaringan. Silakan coba lagi.";
        errorMessageDiv.classList.remove("hidden");
        console.error("Fetch Error:", error);
      } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
      }
    });
  }

  function overlayInfo() {
    const overlay = document.getElementById("Overlay-Info");
    const buttonOpen = document.getElementById("infoForum");
    const buttonClose = document.getElementById("Close-Info");

    if (!overlay || !buttonOpen || !buttonClose) return;

    const openModal = () => {
      overlay.classList.remove("hidden");
    };

    const closeModal = () => {
      overlay.classList.add("hidden");
    };

    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) closeModal();
    });

    buttonOpen.addEventListener("click", openModal);
    buttonClose.addEventListener("click", closeModal);
  }

  function handleSendMessage(event, elements) {
    event.preventDefault();
    const {
      hiddenInput,
      fileInput,
      chatInput,
      removePreview,
      updateTextInputState,
    } = elements;
    const message = hiddenInput.value.trim();
    const file = fileInput.files[0];
    if (!message && !file) {
      alert("Pesan atau file tidak boleh kosong!");
      return;
    }
    const formData = new FormData();
    formData.append("message", message);
    if (file) {
      formData.append("attachment", file, file.name);
    }
    console.log("Data yang akan dikirim:");
    for (let [key, value] of formData.entries()) {
      console.log(`${key}:`, value);
    }
    alert("Form siap dikirim! Lihat data di console.");
    chatInput.innerHTML = "";
    removePreview();
    updateTextInputState();
  }

  function setupChatForm() {
    const chatForm = document.getElementById("chat-form");
    if (!chatForm) return;

    const chatInput = document.getElementById("Chat-Input");
    const placeholder = document.getElementById("placeholder");
    const hiddenInput = document.getElementById("message");
    const uploadButton = document.getElementById("Upload-Image");
    const fileInput = document.getElementById("imageInput");
    const previewContainer = document.getElementById("preview-container");
    const previewImage = document.getElementById("preview-image");
    const previewFilename = document.getElementById("preview-filename");
    const removePreviewButton = document.getElementById("remove-preview");

    if (!chatInput) return;

    const elements = {
      hiddenInput,
      fileInput,
      chatInput,
      removePreview: () => {
        previewContainer.classList.add("hidden");
        fileInput.value = "";
        previewImage.src = "";
      },
      updateTextInputState: () => {
        const textContent = chatInput.innerText;
        placeholder.style.display =
          textContent.trim() === "" ? "block" : "none";
        hiddenInput.value = textContent;
      },
    };
    chatInput.addEventListener("input", elements.updateTextInputState);
    uploadButton.addEventListener("click", () => fileInput.click());
    const fileIconUrl = `${BASEURL}/src/asset/image/file.png`;
    fileInput.addEventListener("change", (event) => {
      const file = event.target.files[0];
      if (!file) return;
      previewFilename.textContent = file.name;
      previewImage.src = file.type.startsWith("image/")
        ? URL.createObjectURL(file)
        : fileIconUrl;
      previewContainer.classList.remove("hidden");
    });
    removePreviewButton.addEventListener("click", elements.removePreview);
    chatForm.addEventListener("submit", (event) =>
      handleSendMessage(event, elements)
    );
    elements.updateTextInputState();
  }

  function modalEnrol() {
    const modal = document.getElementById("modal-enrol-dosen");
    const buttonEnrol = document.getElementById("enrolButton");
    const enrolInput = document.getElementById("enrolKey");
    const errorMessage = document.getElementById("message-error");
    const key = "PNJ";

    buttonEnrol.addEventListener("click", function (event) {
      event.preventDefault();

      const enrolInputValue = enrolInput.value.trim();

      if (enrolInputValue === "") {
        errorMessage.textContent = "Enrol Key is required!";
        errorMessage.classList.remove("hidden");
        enrolInput.focus();
        return;
      }

      if (enrolInputValue === key) {
        modal.classList.add("hidden");
        errorMessage.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
      } else {
        errorMessage.textContent = "Enrol Key Wrong!!";
        errorMessage.classList.remove("hidden");
      }
    });
  }

  showPasswordToggle();
  avatarUpload();
  sidebarToggle();
  openModal();
  createForum();
  overlayInfo();
  setupChatForm();
  previewPost();
  modalEnrol();
  handleRegist()
});
