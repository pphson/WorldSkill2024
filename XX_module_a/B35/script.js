const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

// vẽ tam giác
function drawTriangle(x1, y1, x2, y2, x3, y3) {
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.lineTo(x3, y3);
    ctx.closePath();
    ctx.fill();
}

// hàm đệ quy fractal
function sierpinski(x1, y1, x2, y2, x3, y3, depth) {
    if (depth === 0) {
        drawTriangle(x1, y1, x2, y2, x3, y3);
        return;
    }

    // tính trung điểm
    const mx12 = (x1 + x2) / 2;
    const my12 = (y1 + y2) / 2;

    const mx23 = (x2 + x3) / 2;
    const my23 = (y2 + y3) / 2;

    const mx31 = (x3 + x1) / 2;
    const my31 = (y3 + y1) / 2;

    // gọi đệ quy 3 tam giác con
    sierpinski(x1, y1, mx12, my12, mx31, my31, depth - 1);
    sierpinski(mx12, my12, x2, y2, mx23, my23, depth - 1);
    sierpinski(mx31, my31, mx23, my23, x3, y3, depth - 1);
}

// xử lý nút bấm
function drawFractal() {
    const level = parseInt(document.getElementById("level").value);

    if (isNaN(level) || level < 0 || level > 10) {
        alert("Please enter a valid number!");
        return;
    }

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // tam giác lớn ban đầu
    const x1 = canvas.width / 2;
    const y1 = 20;

    const x2 = 50;
    const y2 = canvas.height - 20;

    const x3 = canvas.width - 50;
    const y3 = canvas.height - 20;

    ctx.fillStyle = "black";

    sierpinski(x1, y1, x2, y2, x3, y3, level);
}