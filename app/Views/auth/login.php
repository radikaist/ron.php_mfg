<form action="<?= e(base_url('login')) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" value="<?= e(old('username')) ?>" autocomplete="off">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password">
    </div>

    <button type="submit">Login</button>
</form>