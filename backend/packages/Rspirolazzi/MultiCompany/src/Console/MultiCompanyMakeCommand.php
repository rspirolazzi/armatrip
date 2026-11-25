<?php

namespace Rspirolazzi\MultiCompany\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class MultiCompanyMakeCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:survey
                    {--views : Only scaffold the survey views}
                    {--prefix : Add namespace}
                    {--force : Overwrite existing views by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic survey views and routes';

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'admin/categories/create.blade.stub'=>'admin/categories/create.blade.php',
        'admin/categories/edit.blade.stub'=>'admin/categories/edit.blade.php',
        'admin/categories/fields.blade.stub'=>'admin/categories/fields.blade.php',
        'admin/categories/index.blade.stub'=>'admin/categories/index.blade.php',
        'admin/categories/show.blade.stub'=>'admin/categories/show.blade.php',
        'admin/categories/show_fields.blade.stub'=>'admin/categories/show_fields.blade.php',
        'admin/categories/table.blade.stub'=>'admin/categories/table.blade.php',

        'admin/answers/create.blade.stub'=>'admin/answers/create.blade.php',
        'admin/answers/edit.blade.stub'=>'admin/answers/edit.blade.php',
        'admin/answers/fields.blade.stub'=>'admin/answers/fields.blade.php',
        'admin/answers/index.blade.stub'=>'admin/answers/index.blade.php',
        'admin/answers/show.blade.stub'=>'admin/answers/show.blade.php',
        'admin/answers/show_fields.blade.stub'=>'admin/answers/show_fields.blade.php',
        'admin/answers/table.blade.stub'=>'admin/answers/table.blade.php',

        'admin/questions/create.blade.stub'=>'admin/questions/create.blade.php',
        'admin/questions/edit.blade.stub'=>'admin/questions/edit.blade.php',
        'admin/questions/fields.blade.stub'=>'admin/questions/fields.blade.php',
        'admin/questions/index.blade.stub'=>'admin/questions/index.blade.php',
        'admin/questions/show.blade.stub'=>'admin/questions/show.blade.php',
        'admin/questions/show_fields.blade.stub'=>'admin/questions/show_fields.blade.php',
        'admin/questions/table.blade.stub'=>'admin/questions/table.blade.php',


    ];
    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $controllers = [
        'Admin/CompanyController.stub'=>'Admin/AnswerController.php',
        'Admin/CategoryController.stub'=>'Admin/CategoryController.php',
        'Admin/QuestionController.stub'=>'Admin/QuestionController.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        dd($this->option('prefix'));

        $this->createDirectories();

        $this->exportViews();

        if (! $this->option('views')) {

            $this->exportControllers();

            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__ . '/stubs/make/routes.stub'),
                FILE_APPEND
            );
        }

        $this->info('MultiCompany scaffolding generated successfully.');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
       /* if (! is_dir($directory = resource_path('views/admin/categories'))) {
            mkdir($directory, 0755, true);
        }
        if (! is_dir($directory = resource_path('views/admin/answers'))) {
            mkdir($directory, 0755, true);
        }
        if (! is_dir($directory = resource_path('views/admin/questions'))) {
            mkdir($directory, 0755, true);
        }*/
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        /*foreach ($this->views as $key => $value) {
            if (file_exists($view = resource_path('views/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__.'/stubs/make/views/'.$key,
                $view
            );
        }*/
    }
    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportControllers()
    {

        /*foreach ($this->controllers as $key => $value) {
            if (file_exists($controller = app_path('Http/Controllers/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            file_put_contents(
                app_path('Http/Controllers/'.$value),
                $this->compileControllerStub(__DIR__.'/stubs/make/controllers/'.$key)
            );
        }*/
    }

    /**
     * Compiles the HomeController stub.
     *
     * @param $file
     * @return string
     */
    protected function compileControllerStub($file)
    {
       /* return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents($file)
        );*/
    }
}
