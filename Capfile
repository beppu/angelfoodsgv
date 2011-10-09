load 'deploy' if respond_to?(:namespace) # cap2 differentiator

# Uncomment if you are using Rails' asset pipeline
# load 'deploy/assets'

Dir['vendor/gems/*/recipes/*.rb','vendor/plugins/*/recipes/*.rb'].each { |plugin| load(plugin) }

load 'config/deploy' # remove this line to skip loading any of the default tasks

namespace :my do
  desc "My first task"
  task :hello, :roles => :app do
    run "echo hello"
    puts self.class
    puts self.methods.sort
  end
end

namespace :deploy do
  desc "Nothing for now"
  task :finalize_update do
  end
end

