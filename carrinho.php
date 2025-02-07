<?php
session_start(); // Inicia a sessão

// Inclui a conexão com o banco de dados
include("includes/conexao.php");
include('includes/header.php');

// Inicializa o carrinho, se ainda não estiver na sessão
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Função para validar e sanitizar o ID do produto
function validar_id_produto($id) {
    return filter_var($id, FILTER_VALIDATE_INT) && $id > 0;
}

// Verifica se a ação de adicionar ao carrinho foi chamada
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id']) && validar_id_produto($_GET['id'])) {
    $product_id = $_GET['id'];
    // Verifica se o produto já está no carrinho
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1; // Incrementa a quantidade
    } else {
        $_SESSION['cart'][$product_id] = 1; // Adiciona o produto com quantidade 1
    }
    // Redireciona de volta ao carrinho após adicionar
    header("Location: carrinho.php");
    exit();
}

// Verifica se o usuário deseja remover um item do carrinho
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id']) && validar_id_produto($_GET['id'])) {
    $product_id = $_GET['id'];
    unset($_SESSION['cart'][$product_id]); // Remove o item do carrinho
    // Redireciona de volta ao carrinho após remover
    header("Location: carrinho.php");
    exit();
}

// Função para calcular o total do carrinho
function calcular_total() {
    global $pdo;
    $total = 0;
    $ids = implode(',', array_keys($_SESSION['cart']));
    
    // Verifica se há produtos no carrinho
    if (!empty($ids)) {
        $stmt = $pdo->prepare("SELECT id, preco FROM lanches WHERE id IN ($ids)");
        $stmt->execute();
        $lanches = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $lanches_map = [];
        foreach ($lanches as $lanche) {
            $lanches_map[$lanche['id']] = $lanche['preco'];
        }
        
        // Calcula o total
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            if (isset($lanches_map[$product_id])) {
                $total += $lanches_map[$product_id] * $quantity;
            }
        }
    }

    return $total;
}

// Função para atualizar a quantidade de um produto no carrinho
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantidade'] as $product_id => $quantidade) {
        if (validar_id_produto($product_id) && $quantidade > 0) {
            $_SESSION['cart'][$product_id] = $quantidade;
        }
    }
    // Redireciona de volta ao carrinho após atualizar
    header("Location: carrinho.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Carrinho de Compras</title>
</head>
<body>

    <div class="cart-container">
        <div class="cart-header">Carrinho de Compras</div>

        <?php if (empty($_SESSION['cart'])): ?>
            <p class="empty-cart-msg">Seu carrinho está vazio. <a href="index.php">Clique aqui</a> para adicionar produtos.</p>
        <?php else: ?>
            <form action="carrinho.php" method="POST">
                <div class="cart-items">
                    <?php
                    foreach ($_SESSION['cart'] as $product_id => $quantity):
                        // Busca os dados do produto
                        $stmt = $pdo->prepare("SELECT nome, preco, imagem FROM lanches WHERE id = :id");
                        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $lanche = $stmt->fetch();
                        if ($lanche):
                    ?>
                        <div class="cart-item">
                            <div class="cart-item-img">
                                <img src="/uploads/<?php echo htmlspecialchars($lanche['imagem']); ?>" alt="<?php echo htmlspecialchars($lanche['nome']); ?>">
                            </div>
                            <div class="cart-item-name"><?php echo htmlspecialchars($lanche['nome']); ?></div>
                            <div class="cart-item-quantity">
                                <input type="number" name="quantidade[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" class="quantity-btn">
                                <a href="carrinho.php?action=remove&id=<?php echo $product_id; ?>" class="btn-remove">Remover</a>
                            </div>
                            <div class="cart-item-price">
                                <button class="price-button">R$ <?php echo number_format($lanche['preco'] * $quantity, 2, ',', '.'); ?></button>
                            </div>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>

                <div class="cart-total">
                    Total: R$ <?php echo number_format(calcular_total(), 2, ',', '.'); ?>
                </div>

                <div class="checkout-btn-container">
                    <a href="finalizar-compra.php" class="checkout-btn green">Finalizar Compra</a>
                    <a href="index.php" class="checkout-btn blue">Escolher mais produtos</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>

<?php include('includes/footer.php'); ?>
