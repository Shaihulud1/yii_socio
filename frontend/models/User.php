<?php
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getNickNameUrl()
    {
        return $this->nickname ? $this->nickname : $this->getId();
    }

    public function followUser($userTarget)
    {
        if($this->getId() == $userTarget->getId())
            throw new NotFoundHttpException();
        $redis = Yii::$app->redis;
        $redis->sadd("user:{$this->getId()}:subscriptions", $userTarget->getId());
        $redis->sadd("user:{$userTarget->getId()}:followers", $this->getId());
    }

    public function unFollowUser($userTarget)
    {
        if($this->getId() == $userTarget->getId())
            throw new NotFoundHttpException();
        $redis = Yii::$app->redis;
        $redis->srem("user:{$this->getId()}:subscriptions", $userTarget->getId());
        $redis->srem("user:{$userTarget->getId()}:followers", $this->getId());
    }

    public function getSubscriptions()
    {
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:subscriptions";
        $redIds = $redis->smembers($key);
        return User::find()->select('id, username, nickname, email')->where(['id' => $redIds])->orderBy('username')->asArray()->all();
    }

    public function getFollowers()
    {
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:followers";
        $redIds = $redis->smembers($key);
        return User::find()->select('id, username, nickname, email')->where(['id' => $redIds])->orderBy('username')->asArray()->all();
    }

    public function isSubscribe()
    {
        $curUserId = Yii::$app->user->identity->id;
        $redis = Yii::$app->redis;
        return $redis->sismember("user:{$this->getId()}:followers", $curUserId);
    }

    public function getUserPicture()
    {
        return $this->picture && file_exists(Yii::getAlias('@web').'upload/images/profile/'.$this->picture) ? Yii::getAlias('@profilesPictureFolder').'/'.$this->picture : Yii::getAlias('@defaultProfileImage');
    }

    public function isCurrentUser($targetId = false)
    {
        $targetId = $targetId ? $targetId : $this->getId();
        return Yii::$app->user->id == $targetId ? true : false;
    }
}
