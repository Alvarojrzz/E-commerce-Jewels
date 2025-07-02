<?php
include '../includes/db.php';
include '../includes/auth.php';
require_login();

require_once __DIR__ . '/../includes/fpdf/fpdf.php';

if (!isset($_GET['order_id']) || !ctype_digit($_GET['order_id'])) {
    header('Location: tienda.php');
    exit;
}
$order_id = intval($_GET['order_id']);
$userId   = $_SESSION['usuario_id'];

$sql = "SELECT p.id_Pedido, p.fecha_pedido, p.precio_total, p.metodo_pago, f.numero_factura, f.iva, f.nombre_cliente, f.telefono_cliente, f.email_cliente, d.calle,
        d.numero,
        d.piso,
        d.cod_postal,
        d.ciudad,
        d.provincia,
        d.pais
    FROM Pedidos p
    JOIN Direcciones d ON p.id_Direccion = d.id_Direccion
    JOIN Facturas  f ON f.id_Pedido  = p.id_Pedido
    WHERE p.id_Pedido = ? AND p.id_Usuario = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    header('Location: tienda.php');
    exit;
}
$pedido = $res->fetch_assoc();
$stmt->close();

$stmt2 = $conn->prepare("SELECT pp.id_Producto, pp.cantidad, pp.precio_unitario, pr.nombre FROM Pedido_Productos pp JOIN Productos pr ON pp.id_Producto = pr.id_Producto WHERE pp.id_Pedido = ?");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$productos = [];
while ($r = $res2->fetch_assoc()) {
    $productos[] = $r;
}
$stmt2->close();

class PDF extends FPDF {
    protected $colorPrimario   = [44, 62, 80];
    protected $colorSecundario = [52, 152, 219];
    protected $colorFondo      = [245, 245, 245];
    protected $colorBorde      = [220, 220, 220];

    function Header() {
        $this->Image(__DIR__ . '/../assets/img/logo/logo.png', 160, 3, 30);
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor($this->colorPrimario[0], $this->colorPrimario[1], $this->colorPrimario[2]);
        $this->Cell(0, 10, 'FACTURA DE COMPRA', 0, 1, 'C');
    }

    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->SetDrawColor($this->colorBorde[0], $this->colorBorde[1], $this->colorBorde[2]);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(5);
        $this->Cell(0, 5, 'JoyasJewels | Av. de los Jeronimos, 135, 30107 Guadalupe, Murcia', 0, 1, 'C');
        $this->Cell(0, 5, 'Tel: +34 123 456 789 | Email: contacto@joyasjewels.com | CIF: B12345678', 0, 1, 'C');
        $this->Cell(0, 5, 'Página ' . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }

    function TitleBox($text) {
        $this->SetFillColor($this->colorPrimario[0], $this->colorPrimario[1], $this->colorPrimario[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, $text, 0, 1, 'L', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(1);
    }

    function TableHeader($widths, $headers) {
        $this->SetFillColor($this->colorPrimario[0], $this->colorPrimario[1], $this->colorPrimario[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        for ($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 7, $headers[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetFillColor($this->colorFondo[0], $this->colorFondo[1], $this->colorFondo[2]);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$colorBorde = [220, 220, 220];
$colorFondo = [245, 245, 245];
$pdf->SetDrawColor($colorBorde[0], $colorBorde[1], $colorBorde[2]);
$pdf->SetFillColor($colorFondo[0], $colorFondo[1], $colorFondo[2]);

$pdf->Ln(30);
$pdf->Cell(95, 8, 'Numero de factura: ' . $pedido['numero_factura'], 0, 0);
$pdf->Cell(95, 8, 'Fecha de emision: ' . date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])), 0, 1);
$pdf->Cell(0, 8, 'Metodo de pago: ' . ucfirst($pedido['metodo_pago']), 0, 1);
$pdf->Ln(8);

$pdf->TitleBox('Datos de Facturacion');
$pdf->Cell(95, 6, 'Nombre: '   . $pedido['nombre_cliente'],   0, 0);
$pdf->Cell(95, 6, 'Telefono: ' . $pedido['telefono_cliente'], 0, 1);
$pdf->Cell(95, 6, 'Email: '    . $pedido['email_cliente'],    0, 1);
$pdf->Ln(3);

$pdf->TitleBox('Direccion de Envio');
$pdf->Cell(0, 6, 'Direccion: ' . $pedido['calle'] . ', ' . $pedido['numero'] . ($pedido['piso'] ? ' (piso ' . $pedido['piso'] . ')' : ''), 0, 1);
$pdf->Cell(0, 6, $pedido['cod_postal'] . ', ' . $pedido['ciudad'] . ' (' . $pedido['provincia'] . '), ' . $pedido['pais'], 0, 1);
$pdf->Ln(10);

$pdf->TitleBox('Detalle de Productos');
$widths  = [100, 30, 20, 30];
$headers = ['Producto', 'Precio Unitario', 'Cantidad', 'Subtotal'];
$pdf->TableHeader($widths, $headers);

$fill = false;
foreach ($productos as $item) {
    $subtotal = $item['cantidad'] * $item['precio_unitario'];
    $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);
    $pdf->Cell($widths[0], 8, $item['nombre'],               'LR', 0, 'L', $fill);
    $pdf->Cell($widths[1], 8, number_format($item['precio_unitario'], 2, ',', '.') . ' EUR', 'LR', 0, 'R', $fill);
    $pdf->Cell($widths[2], 8, $item['cantidad'],            'LR', 0, 'C', $fill);
    $pdf->Cell($widths[3], 8, number_format($subtotal, 2, ',', '.') . ' EUR',           'LR', 1, 'R', $fill);
    $fill = !$fill;
}
$pdf->Cell(array_sum($widths), 0, '', 'T');
$pdf->Ln(8);

$neto = $pedido['precio_total'] - $pedido['iva'];
$pdf->Cell(0, 7, 'Subtotal: ' . number_format($neto, 2, ',', '.') . ' EUR',    0, 1, 'R');
$pdf->Cell(0, 7, 'IVA (21%): ' . number_format($pedido['iva'], 2, ',', '.') . ' EUR', 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'TOTAL: ' . number_format($pedido['precio_total'], 2, ',', '.') . ' EUR', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);

$pdf->Ln(15);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Gracias por su compra en JoyasJewels', 0, 1, 'C');
$pdf->Cell(0, 5, 'Para cualquier consulta, contacte con contacto@joyasjewels.com', 0, 1, 'C');

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Factura_' . $pedido['numero_factura'] . '.pdf"');
$pdf->Output('D', 'Factura_' . $pedido['numero_factura'] . '.pdf');
exit;
?>
