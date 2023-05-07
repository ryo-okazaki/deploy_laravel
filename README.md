# APP名
deploy_laravel

# 構成
・PHP：8.2  
・Laravel：9.0  
・Composer：Latest（2023-05-07時点）  
・Nginx：1.23.2  

# ローカル環境初期構築
以下のコマンドを実行
```
make install
```

# 本番環境用の.envの運用方法
・ローカルの **laravel/.env.prd** で管理する  
・ECSのイメージ作成時はAWS Systems Manager Parameter Storeから値を取得し、本番環境用の.envを作成する(GitHub Actions)  

※ mainブランチにpushする前に.env.prdとParameter Storeの情報を同期する  
  理由は、mainブランチのpushをトリガーにGitHub Actionsが起動し、CI/CDが実行されるため

# .env.prdとParameter Storeの同期方法
ローカルで以下を実行する
```
sh hooks/to-parameter-store.sh
```
