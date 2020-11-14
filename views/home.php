<?php
$this->title = 'Law';

$handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');
fwrite($handle, 'home view' . PHP_EOL);
fwrite($handle, 'pk' . PHP_EOL);
fwrite($handle, print_r($pk, true) . PHP_EOL);

?>

<div class="container">
  <div class="card-columns d-flex justify-content-center">
    <div class="card lawyers-color">
      <div class="card-block">
        <h4 class="card-title">Lawyers</h4>
        <p class="card-text">Portal for lawyers. Enter if you are offering services as lawyer.</p>
        <ul>
          <li>
            <a class="btn btn-primary" href="/lawyer/login">Login</a>
          </li>
          <li>
            <a class="btn btn-success" href="/lawyer/register">Register</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="card citizens-color">
      <div class="card-block">
        <h4 class="card-title">Citizens</h4>
        <p class="card-text">Portal for citizens. Enter if you are looking for services of lawyer.</p>
        <ul>
          <li>
            <a class="btn btn-primary" href="/citizen/login">Login</a>
          </li>
          <li>
            <a class="btn btn-success" href="/citizen/register">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="footer-copyright text-center py-3">© 2020 Copyright:
    <a href="#"> lawonlineregistry.com</a>
  </div>
</footer>
