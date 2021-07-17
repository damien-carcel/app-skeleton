<?php

declare(strict_types=1);

namespace %namespace%;

%imports%

class %name% extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(%subject_class%::class);
    }
}
