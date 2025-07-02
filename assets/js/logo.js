const canvas = document.getElementById('diamondCanvas');
const ctx = canvas.getContext('2d');

function drawDiamondWithText(text) {
  const diamondColor = "#c8e5eb";
  const borderColor  = "#006064";
  const borderWidth  = 2;
  const size         = 60;
  const padding      = 10;

  ctx.font = "bold 14px Georgia, serif";
  const textMetrics = ctx.measureText(text);
  const textWidth   = Math.ceil(textMetrics.width);
  const textHeight  = 14;     

  const totalWidth  = Math.max(size, textWidth) + padding * 2;
  const totalHeight = size * 0.6 + size * 0.4 + textHeight + padding * 3;

  canvas.width  = totalWidth;
  canvas.height = totalHeight;

  const centerX = totalWidth / 2;
  const centerY = padding + size * 0.4;

  const topY    = centerY - size * 0.4;
  const midY    = centerY;
  const botY    = centerY + size * 0.6;
  const halfTop = size * 0.7 / 2;
  const halfMax = size / 2;

  const topPoint           = { x: centerX,        y: topY };
  const leftTopPoint       = { x: centerX - halfTop,  y: midY };
  const rightTopPoint      = { x: centerX + halfTop,  y: midY };
  const leftWidestPoint    = { x: centerX - halfMax,  y: midY };
  const rightWidestPoint   = { x: centerX + halfMax,  y: midY };
  const bottomPoint        = { x: centerX,        y: botY };

  function drawFacet(points) {
    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);
    points.slice(1).forEach(p => ctx.lineTo(p.x, p.y));
    ctx.closePath();
    ctx.fillStyle   = diamondColor;
    ctx.fill();
    ctx.strokeStyle = borderColor;
    ctx.lineWidth   = borderWidth;
    ctx.stroke();
  }

  drawFacet([ topPoint, leftTopPoint,  rightTopPoint ]);
  drawFacet([ topPoint, rightTopPoint, rightWidestPoint ]);
  drawFacet([ topPoint, leftTopPoint,  leftWidestPoint ]);
  drawFacet([ leftTopPoint,  leftWidestPoint,  bottomPoint ]);
  drawFacet([ rightTopPoint, rightWidestPoint, bottomPoint ]);

  ctx.beginPath();
  ctx.moveTo(leftWidestPoint.x, midY);
  ctx.lineTo(rightWidestPoint.x, midY);
  ctx.stroke();
  ctx.font         = "bold 14px Georgia, serif";
  ctx.fillStyle    = "#E0A800";
  ctx.textAlign    = "center";
  ctx.textBaseline = "top"; 
  ctx.fillText(text, centerX, botY + padding);
}

window.onload = () => drawDiamondWithText("JoyasJewels");
