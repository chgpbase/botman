<?php

namespace BotMan\BotMan\Messages\Outgoing;

use BotMan\BotMan\Interfaces\QuestionActionInterface;
use BotMan\BotMan\Interfaces\WebAccess;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use JsonSerializable;
use BotMan\BotMan\Messages\Attachments\Attachment;


class Question implements JsonSerializable, WebAccess
{
    /** @var array */
    protected $actions;

    /** @var string */
    protected $text;

    /** @var string */
    protected $callback_id;

    /** @var string */
    protected $fallback;

    protected $attachment;

    /**
     * @param string $text
     *
     * @return static
     */
    public static function create($text,  Attachment $attachment = null)
    {
        return new static($text, $attachment);
    }

    /**
     * @param string $text
     */
    public function __construct($text, Attachment $attachment = null)
    {
        $this->text = $text;
        $this->actions = [];
        $this->attachment = $attachment;

    }

    /**
     * Set the question fallback value.
     *
     * @param string $fallback
     * @return $this
     */
    public function fallback($fallback)
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Set the callback id.
     *
     * @param string $callback_id
     * @return $this
     */
    public function callbackId($callback_id)
    {
        $this->callback_id = $callback_id;

        return $this;
    }

    public function addAction(QuestionActionInterface $action)
    {
        $this->actions[] = $action->toArray();

        return $this;
    }

    /**
     * @param \BotMan\BotMan\Messages\Outgoing\Actions\Button $button
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->actions[] = $button->toArray();

        return $this;
    }

    /**
     * @param array $buttons
     * @return $this
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            $this->actions[] = $button->toArray();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'text' => $this->text,
            'fallback' => $this->fallback,
            'callback_id' => $this->callback_id,
            'actions' => $this->actions,
        ];
    }

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function getButtons()
    {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the instance as a web accessible array.
     * This will be used within the WebDriver.
     *
     * @return array
     */
    public function toWebDriver()
    {
        return [
            'type' => (count($this->actions) > 0) ? 'actions' : 'text',
            'text' => $this->text,
            'fallback' => $this->fallback,
            'callback_id' => $this->callback_id,
            'actions' => $this->actions,
        ];
    }

    /**
     * @param \BotMan\BotMan\Messages\Attachments\Attachment $attachment
     * @return $this
     */
    public function withAttachment(Attachment $attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @return \BotMan\BotMan\Messages\Attachments\Attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
}
