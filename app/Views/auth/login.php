<form action="<?= e(base_url('login')) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username" value="<?= e(old('username')) ?>" autocomplete="off" placeholder="Masukkan username">
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" placeholder="Masukkan password">
    </div>

    <button class="btn" type="submit">Sign In</button>
</form>