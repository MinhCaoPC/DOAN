// js/scroll-navigation.js

document.addEventListener("DOMContentLoaded", () => {
  const scrollToSection = (btnSelector, targetSelector) => {
    const button = document.querySelector(btnSelector);
    const target = document.querySelector(targetSelector);
    if (button && target) {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        // Cuộn mượt đến phần đích
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      });
    }
  };

  // Liên kết từng nút trong phần "Trải nghiệm"
  scrollToSection("#btn-dia-danh", "#section-dia-danh");
  scrollToSection(".experience-item:nth-child(2)", "#section-am-thuc");
  scrollToSection(".experience-item:nth-child(3)", "#section-nghi-duong");
  scrollToSection(".experience-item:nth-child(4)", "#section-hoat-dong");
});

