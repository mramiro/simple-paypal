<form class="paypal-button" method="POST" action="<?= $this->formAction ?>" target="<?= $this->formTarget ?>">
<?php foreach ($this->getVars() as $key => $value) { ?>
<input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
<?php } ?>
<button class="paypal-button large" type="submit"><?= $this->label ?></button>
</form>
