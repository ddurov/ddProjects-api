<?php declare(strict_types=1);

namespace Api\Models;

use Core\Models\Model;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "updates")]
class UpdateModel extends Model
{
	#[Column(type: Types::TEXT)]
	private string $product;
	#[Column(type: Types::TEXT)]
	private string $versionName;
	#[Column(type: Types::INTEGER)]
	private int $versionCode;
	#[Column(type: Types::TEXT)]
	private string $path;
	#[Column(type: Types::TEXT)]
	private string $description;

	/**
	 * @param string $product
	 * @param string $versionName
	 * @param int $versionCode
	 * @param string $path
	 * @param string $description
	 */
	public function __construct(string $product, string $versionName, int $versionCode, string $path, string $description)
	{
		$this->product = $product;
		$this->versionName = $versionName;
		$this->versionCode = $versionCode;
		$this->path = $path;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getProduct(): string
	{
		return $this->product;
	}

	/**
	 * @return string
	 */
	public function getVersionName(): string
	{
		return $this->versionName;
	}

	/**
	 * @return int
	 */
	public function getVersionCode(): int
	{
		return $this->versionCode;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

}