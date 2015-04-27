<?php

namespace Solarium\Entity;

use INUtils\Entity\PostEntity;
interface EntityInterface {

	/**
	 * @return array $toArray
	 */
	public function toArray();

	/**
	 * @return PostEntity
	 */
	public function getPostEntity();
}