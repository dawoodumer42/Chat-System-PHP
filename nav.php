<style>
.my-badge{
  background-color: blue;
  padding: 5px;
  border-radius: 8px;

}

.nav-item{
  /* background-color: darkslategray; */
  margin:5px;
  /* border-radius: 10px; */
}

</style>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="inbox.php">Chat System</a>
    
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Navbar links -->
    <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="inbox.php">Inbox</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="users.php">Users <span class="my-badge">5</span></a>
        </li>
        
        <li class="nav-item">
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
          </li>
        </li>
      </ul>
    </div>
  </nav>