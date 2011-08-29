set :application, "AngelfoodsGV"
set :repository,  "git@github.com:beppu/angelfoodsgv.git"

set :scm, :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `git`, `mercurial`, `perforce`, `subversion` or `none`

role :web, "bavl.org"                          # Your HTTP server, Apache/etc
role :app, "bavl.org"                          # This may be the same as your `Web` server
role :db,  "bavl.org", :primary => true        # This is where Rails migrations will run
role :db,  "bavl.org"

# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
