<?php

use OpenTelemetry\SDK\Common\Configuration\Variables;

return [
    'disabled' => filter_var(
        env(Variables::OTEL_SDK_DISABLED, empty(env(Variables::OTEL_EXPORTER_OTLP_ENDPOINT))),
        FILTER_VALIDATE_BOOLEAN
    ),
];
