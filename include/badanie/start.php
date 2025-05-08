<?php
global $przed_badaniem;
$zgoda = $przed_badaniem['zgoda'];
$zrozumienie = $przed_badaniem['zrozumienie'];
?>
<div id="start">
    <form action="#" id="form-start">
        <div class="item">
            <?php if($zrozumienie['pytanie']) : ?>
            <strong class="question"><?= $zrozumienie['pytanie']; ?></strong>
            <?php endif; ?>
            <div class="answers">           
                <?php if($zrozumienie['zgoda']) : ?>
                <label for="zrozumienie-tak">
                    <input type="radio" name="zrozumienie" value="tak" id="zrozumienie-tak">
                    <span><?= $zrozumienie['zgoda']; ?></span>
                </label>       
                <?php endif; ?>
                <?php if($zrozumienie['brak_zgody']) : ?>
                <label for="zrozumienie-nie">
                    <input type="radio" name="zrozumienie" value="nie" id="zrozumienie-nie">
                    <span><?= $zrozumienie['brak_zgody']; ?></span>
                </label>
                <?php endif; ?>
            </div>
        </div>
        <?php if($zgoda['naglowek']) : ?>
        <h3><?= $zgoda['naglowek']; ?></h3>
        <?php endif; ?>
        <?php if($zgoda['opis']) : ?>
        <div class="text">
            <?= $zgoda['opis']; ?>
        </div>
        <?php endif; ?>

        <div class="item">
            <?php if($zgoda['pytanie']) : ?>
            <strong class="question"><?= $zgoda['pytanie']; ?></strong>
            <?php endif; ?>
            <div class="answers">           
                <?php if($zgoda['zgoda']) : ?>
                <label for="udzial-tak">
                    <input type="radio" name="udzial" value="tak" id="udzial-tak">
                    <span><?= $zgoda['zgoda']; ?></span>
                </label>       
                <?php endif; ?>
                <?php if($zgoda['brak_zgody']) : ?>
                <label for="udzial-nie">
                    <input type="radio" name="udzial" value="nie" id="udzial-nie">
                    <span><?= $zgoda['brak_zgody']; ?></span>
                </label>
                <?php endif; ?>
            </div>
        </div>

        <div class="line-btn">
            <button class="btn btn-blue-line"><?= __('Dalej','migracja'); ?></button>
        </div>
    </form>
</div>