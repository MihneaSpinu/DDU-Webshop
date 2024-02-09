<div class="container">
  <div class="mx-auto card col-12 col-sm-12 col-md-12 col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo escape($data->name); ?></h3>
      </div>
      <div class="panel-body">
        <div class="">
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td>Name :</td>
                <td><?php echo escape($data->name); ?></td>
              </tr>
              <tr>
                <td>Username :</td>
                <td><?php echo escape($data->username); ?></td>
              </tr>
              <tr>
                <td>Date Joined :</td>
                <td><?php echo escape($data->joined); ?></td>
              </tr>
            </tbody>
          </table>
          <div class="mb-3">
            <a href="update-account.php" class="btn btn-primary">Update Information</a>
            <a href="index.php" class="btn btn-primary">Back</a>
            <a href="delete-account.php" class="btn btn-danger">Delete Account</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>