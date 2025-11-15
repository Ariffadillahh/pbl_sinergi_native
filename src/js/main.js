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

  showPasswordToggle();
  avatarUpload();
  sidebarToggle();
  openModal();
  overlayInfo();
});
