<?php

namespace App\Models\Requests\Writer;

use Illuminate\Validation\Rule;
use App\Models\Requests\PostRequest;
use App\Service\Contracts\IWriterService;

class SaveChapterPostRequest extends PostRequest
{
    private $writerService;

    public function __construct(IWriterService $writerService) {
        $this->writerService = $writerService;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->validationData();
        $story = $this->writerService->GetStoryByID($data['story_id']);
        return (!is_null($story) && $this->user()->username == $story['username']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chapter_title' => ['required', 'string', 'min:5', 'max:50'],
            'content' => ['required', 'string', 'min:10', 'max:16777214'],
            'last_chapter' => ['sometimes', 'required', 'string', Rule::in(['Yes'])],
            'story_id' => ['required', 'exists:stories,story_id'],
            'chapter_id' => ['sometimes', 'required', 'exists:chapters,chapter_id']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'chapter_title.required' => 'Chapter Title must not be empty',
            'chapter_title.min' => 'Chapter Title must have at least :min characters',
            'chapter_title.max' => 'Chapter Title must not exceed :max characters',

            'content.required' => 'Content must not be empty',
            'content.min' => 'Content must have at least :min characters',
            'content.max' => 'Content must not exceed :max characters',

            'last_chapter.required' => 'Invalid checkbox input',
            'last_chapter.string' => 'Invalid checkbox input',
            'last_chapter.in' => 'Invalid checkbox input',

            'story_id.exists' => 'Story does not exist',

            'chapter_id.exists' => 'Chapter does not exist',
        ];
    }

    public function validated()
    {
        $validated = parent::validated();
        return array_merge($validated, [
            'username' => $this->user()->username,
            'last_chapter' => (isset($validated['last_chapter']) && $validated['last_chapter'] === 'Yes')
        ]);
    }
}
