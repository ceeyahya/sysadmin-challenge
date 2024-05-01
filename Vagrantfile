Vagrant.configure("2") do |config|
  config.vm.box = "debian/bookworm64"
  config.vm.network "private_network", ip: "10.0.2.15"
  config.vm.provision "ansible", playbook: "playbook.yml"
  config.vm.provider "virtualbox" do |vb|
   vb.memory = "1024"
  end
end
