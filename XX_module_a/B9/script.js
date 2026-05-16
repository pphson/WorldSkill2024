const compare = document.querySelector(".compare");

const ring = document.createElement("div");
ring.className = "cursor-ring";
compare.appendChild(ring);

let dragging = false;

const move = (e) => {
    const r = compare.getBoundingClientRect();
    ring.style.left = e.clientX - r.left + "px";
    ring.style.top = e.clientY - r.top + "px";
};

compare.addEventListener("mousedown", (e) => {
    dragging = true;
    ring.style.display = "block";
    move(e);
});

compare.addEventListener("mousemove", (e) => dragging && move(e));

document.addEventListener("mouseup", () => {
    dragging = false;
    ring.style.display = "none";
});

compare.addEventListener("mouseleave", () => {
    if (!dragging) ring.style.display = "none";
});