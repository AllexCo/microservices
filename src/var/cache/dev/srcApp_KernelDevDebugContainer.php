<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerNsFh9Ic\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerNsFh9Ic/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerNsFh9Ic.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerNsFh9Ic\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerNsFh9Ic\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'NsFh9Ic',
    'container.build_id' => '0c6c86d3',
    'container.build_time' => 1587996578,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerNsFh9Ic');
