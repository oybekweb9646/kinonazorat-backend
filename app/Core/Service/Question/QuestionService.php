<?php

namespace App\Core\Service\Question;

use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\Question\QuestionAuthorityRepository;
use App\Core\Repository\Question\QuestionRepository;
use App\Http\Requests\Question\QuestionRequest;
use App\Models\Question;
use App\Models\QuestionAuthority;
use Illuminate\Database\Eloquent\Collection;

readonly class QuestionService
{
    public function __construct(
        private Transaction                 $transaction,
        private QuestionRepository          $questionRepository,
        private QuestionAuthorityRepository $questionAuthorityRepository,
        private AuthorityRepository         $authorityRepository
    )
    {
    }

    public function create(QuestionRequest $request): Question
    {
        $question = new Question();

        $question->fill($request->all());

        $this->transaction->wrap(function () use ($question) {
            $question->save();
        });

        return $question;
    }

    public function update(QuestionRequest $request, int $id): Question
    {

        $question = $this->questionRepository->getByIdObject($id);
        $question->fill($request->all());

        $this->transaction->wrap(function () use ($question) {
            $question->save();
        });

        return $question;
    }

    public function delete(int $id): question
    {
        $question = $this->questionRepository->getByIdObject($id);

        $this->transaction->wrap(function () use ($question) {
            $question->delete();
        });

        return $question;
    }

    public function read(): Collection
    {
        if (is_null(auth()->user()->authority_id)) {
            throw new \DomainException('Foydalanuvchiga tashkilot biriktirilmagan');
        }

        $questions = $this->questionRepository->findAllAsObject();
        $authority = $this->authorityRepository->getById(auth()->user()->authority_id);

        $this->transaction->wrap(function () use ($questions, $authority) {
            foreach ($questions as $question) {
                $this->questionSave($question);
            }

            $authority->is_checked_question = true;
            $authority->save();
        });


        return $questions;
    }

    private function questionSave(Question $question): void
    {
        $questionAuthority = $this->questionAuthorityRepository->getByAuthorityAndQuestion(auth()->user()->authority_id, $question->id);
        if (is_null($questionAuthority)) {
            $questionAuthority = new QuestionAuthority();
        }
        $questionAuthority->authority_id = auth()->user()->authority_id;
        $questionAuthority->question_id = $question->id;
        $questionAuthority->stir = auth()->user()->stir;

        $this->transaction->wrap(function () use ($questionAuthority) {
            $questionAuthority->save();
        });
    }
}
