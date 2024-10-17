<?php
namespace App\Service;


class EncryptService{

	private string $cipherMethod = "BF-CBC";
	private int $options = 0;
	private string|false $encryption_key = b"÷),-jR\x16g“QÇR—ÁÙ9";
	private int $encryption_iv = 12345678;
	private string $encryption;

	public function encrypt(string $word): self
	{
		$this->encryption = openssl_encrypt($word, $this->cipherMethod,
		$this->encryption_key, $this->options, $this->encryption_iv);

		return $this;
	}

	public function decrypt(string $word): string|false
	{
		$decryption = openssl_decrypt($word, $this->cipherMethod,
		$this->encryption_key, $this->options, $this->encryption_iv);

		return $decryption;
	}

	public function getEncrypt(): string
	{
		return $this->encryption;
	}

}
