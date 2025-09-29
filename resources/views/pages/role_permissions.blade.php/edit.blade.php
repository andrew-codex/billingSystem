<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
  <title>Edit Role Permissions</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8fafc;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 700px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .role-select {
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
    }

    select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .permissions {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 25px;
    }

    .permission {
      background: #f1f5f9;
      padding: 12px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s;
    }

    .permission:hover {
      background: #e2e8f0;
    }

    input[type="checkbox"] {
      transform: scale(1.2);
    }

    .btn {
      display: block;
      width: 100%;
      background: #2563eb;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #1d4ed8;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Role Permissions</h2>

    <div class="role-select">
      <label for="role">Select Role:</label>
      <select id="role" name="role">
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
      </select>
    </div>

    <div class="permissions">
      <div class="permission">
        <input type="checkbox" id="manage-products">
        <label for="manage-products">Manage Products</label>
      </div>
      <div class="permission">
        <input type="checkbox" id="manage-orders">
        <label for="manage-orders">Manage Orders</label>
      </div>
      <div class="permission">
        <input type="checkbox" id="manage-users">
        <label for="manage-users">Manage Users</label>
      </div>
      <div class="permission">
        <input type="checkbox" id="view-reports">
        <label for="view-reports">View Reports</label>
      </div>
    </div>

    <button class="btn">Save Changes</button>
  </div>
</body>
</html>
