<?php
declare(strict_types=1);

namespace App\Task\Api;

use App\Entity\Cache\ImgCaptchaCache;
use App\Exceptions\ValidatorInvalidParamsException;
use App\Utils\RedisUtils;

class CheckImgCodeTask
{
    private RedisUtils $redisUtils;

    /**
     * CheckImgCodeTask constructor.
     * @param RedisUtils $redisUtils
     */
    public function __construct(RedisUtils $redisUtils)
    {
        $this->redisUtils = $redisUtils;
    }

    /**
     * @param string $key
     * @param string $code
     */
    public function run(string $key, string $code)
    {
        $code = strtoupper($code);
        //检查手机验证码是否正确
        $codeCache = $this->redisUtils->getObject(ImgCaptchaCache::class, $key);
        if (is_null($codeCache) || $codeCache->code !== $code) {
            throw new ValidatorInvalidParamsException('图形验证码错误');
        }
        $this->redisUtils->delObject($codeCache);
    }
}
