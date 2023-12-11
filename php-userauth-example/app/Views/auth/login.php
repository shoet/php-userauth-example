<?php if (session()->getFlashdata('errors')): ?>
    <div style="color: red">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form method='post' action='/signin'>
    <input type='text' name='email' placeholder='Email' />
    <input type='text' name='password' placeholder='Password' />
    <input type='submit' name='submit' value='Login' />
</form>

