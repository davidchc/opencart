<?php
/**
* Template de redirecionamento para o getway de pagamento
*
* Não visto pelo usuário, ao entrar nesta página, o formulário é submetido
* automaticamente.
* @package pagseguro_opencart
* @author ldmotta - ldmotta@gmail.com
* @link motanet.com.br
*/
?>
<div class="buttons">
<table>
<tr>
<td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
<td align="right" id="checkout"><?php echo $this->form; ?></td>
</tr>
</table>
</div>

<script type="text/javascript">
    function checkout() {
        document.pagseguro.submit();
        $.ajax({
            type: 'GET',
            url: 'index.php?route=payment/pagseguro/confirm',
            success: function(t) {
                location = '<?php echo $continue; ?>'
            }
        });
    };
</script>




