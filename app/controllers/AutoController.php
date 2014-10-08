<?php

class AutoController extends Controller 
{
  protected function run()
  {
echo Request::path();
echo '<br>';
exit('AutoController');
  }

}
