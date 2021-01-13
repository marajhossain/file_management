<?php

namespace App\Entity;

use App\Repository\FormRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormRepository::class)
 */
class Form
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\NotBlank
     */
    private $form_submit_type;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $form_token;

    /**
     * @ORM\Column(type="integer")
     */
    private $question_form_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question_answer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payment_slip_attachment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eia_form_attachment;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_by;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updated_by;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updated_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormSubmitType(): ?string
    {
        return $this->form_submit_type;
    }

    public function setFormSubmitType(string $form_submit_type): self
    {
        $this->form_submit_type = $form_submit_type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFormToken(): ?string
    {
        return $this->form_token;
    }

    public function setFormToken(?string $form_token): self
    {
        $this->form_token = $form_token;

        return $this;
    }

    public function getQuestionFormId(): ?int
    {
        return $this->question_form_id;
    }

    public function setQuestionFormId(int $question_form_id): self
    {
        $this->question_form_id = $question_form_id;

        return $this;
    }

    public function getQuestionAnswer(): ?string
    {
        return $this->question_answer;
    }

    public function setQuestionAnswer(string $question_answer): self
    {
        $this->question_answer = $question_answer;

        return $this;
    }

    public function getPaymentSlipAttachment(): ?string
    {
        return $this->payment_slip_attachment;
    }

    public function setPaymentSlipAttachment(?string $payment_slip_attachment): self
    {
        $this->payment_slip_attachment = $payment_slip_attachment;

        return $this;
    }

    public function getEiaFormAttachment(): ?string
    {
        return $this->eia_form_attachment;
    }

    public function setEiaFormAttachment(?string $eia_form_attachment): self
    {
        $this->eia_form_attachment = $eia_form_attachment;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getCreatedTime(): ?int
    {
        return $this->created_time;
    }

    public function setCreatedTime(int $created_time): self
    {
        $this->created_time = $created_time;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?int $updated_by): self
    {
        $this->updated_by = $updated_by;

        return $this;
    }

    public function getUpdatedTime(): ?int
    {
        return $this->updated_time;
    }

    public function setUpdatedTime(?int $updated_time): self
    {
        $this->updated_time = $updated_time;

        return $this;
    }

    
}
