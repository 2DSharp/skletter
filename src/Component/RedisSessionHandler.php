<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component;


use Predis\Client;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

class RedisSessionHandler implements SessionInterface
{
    /**
     * @var Client $predis
     */
    private $predis;

    private $name = 'SkletterID';
    /**
     * @var bool $started
     */
    private $started = false;
    private $token;

    public function __construct(Client $predis)
    {
        $this->predis = $predis;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateRandomToken(): string
    {
        return implode('-', [
            bin2hex(random_bytes(4)),
            bin2hex(random_bytes(2)),
            bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)) . bin2hex(random_bytes(1)),
            bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)) . bin2hex(random_bytes(1)),
            bin2hex(random_bytes(6))
            ]) . md5($_SERVER['REMOTE_ADDR'] . mt_rand());
    }

    /**
     * Starts the session storage.
     *
     * @return bool True if session started
     *
     * @throws \RuntimeException if session fails to start
     */
    public function start()
    {
        try {
            if (!isset($_COOKIE[$this->name])) {
                $this->token = $this->generateRandomToken();
                setcookie($this->name, $this->token, 0);
            }

            if (!isset($_COOKIE[$this->name]))
                $_COOKIE[$this->name] = $this->token;

            $this->started = true;
            return true;

        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to generate secure token');
        }
    }

    /**
     * Returns the session ID.
     *
     * @return string The session ID
     * @throws \Exception
     */
    public function getId()
    {
        if (!$this->started)
            $this->start();

        return $_COOKIE[$this->name];
    }

    /**
     * Sets the session ID.
     *
     * @param string $id
     */
    public function setId($id)
    {
        setcookie($this->name, $id);
    }

    /**
     * Returns the session name.
     *
     * @return mixed The session name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the session name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Invalidates the current session.
     *
     * Clears all session attributes and flashes and regenerates the
     * session and deletes the old session from persistence.
     *
     * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                      will leave the system settings unchanged, 0 sets the cookie
     *                      to expire with browser session. Time is in seconds, and is
     *                      not a Unix timestamp.
     *
     * @return bool True if session invalidated, false if error
     */
    public function invalidate($lifetime = null)
    {
        // TODO: Implement invalidate() method.
    }

    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     *
     * @param bool $destroy Whether to delete the old session or leave it to garbage collection
     * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                       will leave the system settings unchanged, 0 sets the cookie
     *                       to expire with browser session. Time is in seconds, and is
     *                       not a Unix timestamp.
     *
     * @return bool True if session migrated, false if error
     */
    public function migrate($destroy = false, $lifetime = null)
    {
        // TODO: Implement migrate() method.
    }

    /**
     * Force the session to be saved and closed.
     *
     * This method is generally not required for real sessions as
     * the session will be automatically saved at the end of
     * code execution.
     */
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     * Checks if an attribute is defined.
     *
     * @param string $name The attribute name
     *
     * @return bool true if the attribute is defined, false otherwise
     * @throws \Exception
     */
    public function has($name)
    {
        return $this->predis->hexists($this->getId(), $name);
    }

    /**
     * Returns an attribute.
     *
     * @param string $name The attribute name
     * @param mixed $default The default value if not found
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($name, $default = null)
    {
        $value = $this->predis->hmget($this->getId(), [$name]);
        if (empty($value[0]))
            return $default;

        return $value[0];
    }

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function set($name, $value)
    {
        $this->predis->hmset($this->getId(), [$name => $value]);
    }

    /**
     * Returns attributes.
     *
     * @return array Attributes
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * Sets attributes.
     *
     * @param array $attributes Attributes
     */
    public function replace(array $attributes)
    {
        // TODO: Implement replace() method.
    }

    /**
     * Removes an attribute.
     *
     * @param string $name
     *
     * @return mixed The removed value or null when it does not exist
     * @throws \Exception
     */
    public function remove($name)
    {
        $this->predis->hdel($this->getId(), [$name]);
    }

    /**
     * Clears all attributes.
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * Checks if the session was started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Registers a SessionBagInterface with the session.
     * @param SessionBagInterface $bag
     */
    public function registerBag(SessionBagInterface $bag)
    {
        // TODO: Implement registerBag() method.
    }

    /**
     * Gets a bag instance by name.
     *
     * @param string $name
     *
     * @return SessionBagInterface
     */
    public function getBag($name)
    {
        // TODO: Implement getBag() method.
    }

    /**
     * Gets session meta.
     *
     * @return MetadataBag
     */
    public function getMetadataBag()
    {
        // TODO: Implement getMetadataBag() method.
    }
}