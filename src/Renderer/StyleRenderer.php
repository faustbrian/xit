<?php

declare(strict_types=1);

namespace BombenProdukt\Xit\Renderer;

use BombenProdukt\Xit\Data\Document;
use BombenProdukt\Xit\Element\Style\Checked;
use BombenProdukt\Xit\Element\Style\Due;
use BombenProdukt\Xit\Element\Style\InQuestion;
use BombenProdukt\Xit\Element\Style\Obsolete;
use BombenProdukt\Xit\Element\Style\Ongoing;
use BombenProdukt\Xit\Element\Style\Open;
use BombenProdukt\Xit\Element\Style\Priority;
use BombenProdukt\Xit\Element\Style\Tag;
use BombenProdukt\Xit\Element\Style\Title;
use BombenProdukt\Xit\Enum\ItemStatus;
use BombenProdukt\Xit\Enum\ItemType;

final readonly class StyleRenderer implements RendererInterface
{
    public function render(Document $document): string
    {
        $rendered = '';

        foreach ($document->getGroups() as $group) {
            foreach ($group->getItems() as $line) {
                if ($line->getType() === ItemType::Title) {
                    $rendered .= Title::fromString($line->getContent());

                    continue;
                }

                if ($line->getType() === ItemType::Item || $line->getType() === ItemType::ItemDetails) {
                    $lineContent = '';

                    if ($line->getModifiers()->getHasPriority()) {
                        $priority = '';

                        for ($priorityPadding = 1; $priorityPadding <= $line->getModifiers()->getPriorityPadding(); $priorityPadding++) {
                            $priority .= '.';
                        }

                        for ($priorityLevel = 1; $priorityLevel <= $line->getModifiers()->getPriorityLevel(); $priorityLevel++) {
                            $priority .= '!';
                        }

                        $lineContent .= Priority::fromString($priority).' ';
                    }

                    if ($line->getStatus()) {
                        $rendered .= match ($line->getStatus()) {
                            ItemStatus::Open => Open::fromString($line->getContent()),
                            ItemStatus::Checked => Checked::fromString($line->getContent()),
                            ItemStatus::Ongoing => Ongoing::fromString($line->getContent()),
                            ItemStatus::Obsolete => Obsolete::fromString($line->getContent()),
                            ItemStatus::InQuestion => InQuestion::fromString($line->getContent()),
                        };
                    } else {
                        $rendered .= $line->getContent().' ';
                    }

                    if (\count($line->getModifiers()->getTags())) {
                        foreach ($line->getModifiers()->getTags() as $tag) {
                            $rendered .= Tag::fromString($tag).' ';
                        }
                    }

                    if ($line->getModifiers()->getDue() !== null) {
                        $rendered .= Due::fromString($line->getModifiers()->getDue());
                    }
                }
            }
        }

        return \trim($rendered);
    }
}
