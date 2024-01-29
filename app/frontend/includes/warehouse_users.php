<!-- Select a user -->
<h2 class="text-center mt-5 border-top border-dark font-weight-bold">User Information</h2>
<form class="form-group" method="POST">
    <label for="userSelect">Select User:</label>
    <select name="selectedUser" id="userSelect">
        <?php foreach ($users as $user) : ?>
            <option value="<?php echo $user->username; ?>"><?php echo $user->username; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="userSubmit" value="Select">
</form>

<!-- Print the user information -->
<?php if (isset($selectedUser)) : ?>
    <h3><?php echo $selectedUser->username; ?></h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Amount of Orders</th>
                <th>Username</th>
                <th>Password</th>
                <th>Name</th>
                <th>Is Guest</th>
                <th>Joined</th>
                <th>Email</th>
                <th>Group</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $selectedUser->uid; ?></td>
                <td><?php echo $amountOfOrders; ?></td>
                <td><?php echo $selectedUser->username; ?></td>
                <td><?php echo $selectedUser->password; ?></td>
                <td><?php echo $selectedUser->name; ?></td>
                <td><?php echo $selectedUser->is_guest; ?></td>
                <td><?php echo $selectedUser->joined; ?></td>
                <td><?php echo $selectedUser->email; ?></td>
                <!-- echo user group permissions from id -->
                <td><?php echo $groupPerms[$selectedUser->group_ID]; ?></td>
            </tr>
    </table>
<?php else : ?>
    <p>Please select a user.</p>
<?php endif; ?>