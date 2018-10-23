<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage;

\class_alias(__NAMESPACE__ . '\\Storage', __NAMESPACE__ . '\\Persister');
\class_alias(__NAMESPACE__ . '\\Drivers\\Psr6Storage', __NAMESPACE__ . '\\Psr6Persister');
\class_alias(__NAMESPACE__ . '\\Drivers\\Psr16Storage', __NAMESPACE__ . '\\Psr16Persister');
\class_alias(__NAMESPACE__ . '\\Drivers\\ArrayStorage', __NAMESPACE__ . '\\ArrayPersister');
\class_alias(__NAMESPACE__ . '\\Drivers\\NullableStorage', __NAMESPACE__ . '\\NullablePersister');
\class_alias(__NAMESPACE__ . '\\Drivers\\EmulatingStorage', __NAMESPACE__ . '\\EmulatingPersister');
