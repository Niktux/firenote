<?php

return <<<CONTENT
<?php

namespace $this->namespace\Controllers\Home;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    public function indexAction()
    {
        return \$this->page
            ->setTitle('Home page')
            ->render('home.twig');
    }
}
CONTENT
;