<?php

return <<<CONTENT
<?php

namespace $this->namespace\Controllers\Home;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    public function indexAction()
    {
        return \$this->renderResponse('Home page', 'home.twig');
    }
}
CONTENT
;