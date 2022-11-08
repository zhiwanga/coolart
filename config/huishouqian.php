<?php
$dirname = dirname(dirname(__FILE__));
return [
    // 微信小程序
    'wechat' => [
        'appid' => 'xxx',
        'appsecret' => 'xxx',
    ],
    'alipay' => [
        'appid' => 'xxx',
        'private_key' => 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDZGBkeKQLT68IOcrb/EURfpIKe1/dbiDT5vcL/wNiRsbTzr4GwsyE3mvAPRCIdiMJj55Ni8pBQqdbcv1q/5eccbnZ0Bx95izSbK0b074R0kuPpGcmGDTgmYyMGu01g6V+Y55ox4wZWAeyERLKJ2RbaBm9V3L6H0kWbiW02G854MDXRCqzHCSI/he8uIqglnDWqmpnvOap7lvGyieE3LryN46djBv9Y8TQJGt3xXobdD1PBJP3wmTc7Aob4tN9X+VSfVVafG4jseET/JcOOZ0PU42lWb0FEVFeI+Kmw+Qf+N0bOZn4NJZeaUOoXPwBWyXZBLP37JwIn+ejirmJhu4J7AgMBAAECggEAOmIxtxu+Z/HacXpGXphtM++RNVjNbC9YV+XBwHhDhgMtr+9+SGAUSdkAQIrJRfoWlTmdOJoVV6wlXZ6UsFEK82l9r7yOqWhXkHd2EL8tdxBd/L1zpfp+JEy7Cb9rkumQTRXUMSBl/tFrozK6dToN3DqozIWeM/bHw4mU9l1KDKYjtccohVcqUWI2/BG2HI9otpAXVHph9wa/CodGsSWJzsNXVWrJoAHtw13jK0HJRWvHIxYM97sIbnDMQlovUJNHyHDlvBvDUoOIkVGpcv+ElKyPjRCm2zr/DRIPTueKYY7ObyAK83UMMxPswsKASqiO8/G9qkIJ5jwryERDycL8IQKBgQDsappFET+SRxEvf0+oqdykYsOSuqaZY1ovYD546uOqr/TKYfuzN6qUvRiXqCwaPyz3KTzzHhLkQxyqLPin1IV8Fp42C+Y58IQ1cTBAL7KkmsAtbxpSNkPZxDFs2RkJgjr1srJqe9+bVvKQevd+enP/Hn5jKGZtwn2OxmyVPKi3UQKBgQDrE8BNRIcABYVJhPQcqOm27etsLHWTMATHiCma3ggvA4zZMAQBW/K3NwpFG3k16cpM7E6z89vyEP5TnwkwUApgOp65FejuRh6EAI56OWgCePDfh/zmZlzqtDB26f1YN51TGV9d1xRnE66HhyvDnLtZHrEyJJMsPCWnL+//Kv0CCwKBgDTCAVVCaR5aj6I61er8pQlmJOC8mGsIedqyrp5LRK0VgRoczY+L75wrqq5FUDo3OPCbGkfiwTKBK4dqa78ANR2g6oODGkh5vPc0ICFiw4nqItLAxW3tQCz7lFAHDohJsKCd1STgKeE/gPfvCQYHi5U2F9xG6ry5t01CSH7xP3FRAoGBAKjE3lva5f0q+X1F2ag10M1HVKyt4p+Qau4BoLUR0NhH+Tf4U/fR1piZCGODDOua9GVzhjGF0xOgBadtV0B7Rme9zjRdOYTPjJsCd0firPyRbYVd8UBMn0rVoLHO3dcW3nlJjwElJ/200Kl2bwIaJRsfgOHaBltMt0+nh+svJsPvAoGBAJBuPBkRmYPfbyEvc0SUYBV8eKXAuynxT+VSF+clEWBJjs6sddK1zsG5IkFjhY66WxFydKOvqYxzwJUbagJQ4x9SY1lwRhDWDiUTfkyL9IKlBbO58jXnTI7dUYJqKjmHFwTyypaxgLrXZFVNdOqQj31tVLKD5I4BNWAp7T1lFlJj',
        'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAu1NxAX+P0R37Ap0QvAio4ZocHIxkhW8y8pm2OiMKFKsDC8MbwAAPb/1kFEDSxS/TTV36HFFFHqsY9jgstJlJHZTZoEFhHtkQq6sOzMEG7ZEmujliAKhRYupOeh7tmkNu0Zojr5VfvmcggRW3rEkdehDaUCdAKRmZ04SHwrKyk+vvNM4MnC4wAVYQGTGe3e1SeR6dlkBpfhwpBeOC89SUVZJhktD9RnDmSIHHJc1jSFQns93MWLNkrt2ap4L7gomDb0s3wtZYRHjnhMbc65Gqk0eecEDgkFBuIl7zyw2GYj2I7sV9gyClze27Vfa9bDQWddVJHB10ZgbHW1fAJxJDowIDAQAB'
    ],
    'merchant' => 'xxx', // 商户号
    'key' => 'xxx', // 秘钥
    'private_key_path' => $dirname . '/extend/org/huishouqian/cert/hsq_pri.pfx',
    'public_key_path' => $dirname . '/extend/org/huishouqian/cert/MANDAO_850003352952_pub.cer',
    'private_key_password' => '123456'
];
