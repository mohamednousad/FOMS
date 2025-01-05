document.addEventListener("DOMContentLoaded", function () {
  const table = document.querySelector("#myTable");
  const tableRows = document.querySelectorAll("#myTable tbody tr");
  const inputs = document.querySelectorAll("input, select, textarea");
  const fwBold = document.querySelector(".fw-bold");

  if (table) {
    gsap.from(table, {
      opacity: 0,
      y: 50,
      duration: 1,
      ease: "power2.out",
    });
  }


  if (inputs.length > 0) {
    gsap.from(inputs, {
      opacity: 0,
      y: 20,
      duration: 1,
      delay: 0.5,
      ease: "power2.out",
    });
  }

  if (tableRows.length > 0) {
    gsap.from(tableRows, {
      opacity: 0,
      y: 50,
      stagger: 0.1,
      duration: 0.5,
      ease: "power2.out",
      delay: 1,
    });
  }

  if (fwBold) {
    gsap.from(fwBold, {
      opacity: 0,
      y: 30,
      duration: 1,
      delay: 0.7,
      ease: "power2.out",
    });
  }

});
