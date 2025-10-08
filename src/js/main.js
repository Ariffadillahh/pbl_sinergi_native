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
  modalEnrol();
  handleRegist()
});
