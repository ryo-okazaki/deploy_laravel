terraform {
    required_providers {
        aws = {
            source = "hashicorp/aws"
            version = "~> 3.0"
        }
    }
}

provider "aws" {
    region = "ap-northeast-1"
}

resource "aws_iam_policy" "IAMManagedPolicy" {
    name = "ecsFargateExecRole"
    path = "/"
    policy = <<EOF
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "ssmmessages:CreateControlChannel",
                "ssmmessages:CreateDataChannel",
                "ssmmessages:OpenControlChannel",
                "ssmmessages:OpenDataChannel"
            ],
            "Resource": "*"
        }
    ]
}
EOF
}

resource "aws_codedeploy_app" "CodeDeployApplication" {
    name = "AppECS-demo-laravel-ecs-cluster-ecs-laravel-service-v2"
    compute_platform = "ECS"
}

resource "aws_iam_role" "IAMRole" {
    path = "/"
    name = "CodeDeployRoleForECS"
    assume_role_policy = "{\"Version\":\"2012-10-17\",\"Statement\":[{\"Sid\":\"\",\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"codedeploy.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"}]}"
    max_session_duration = 3600
    tags = {}
}

resource "aws_ecr_repository" "ECRRepository" {
    name = "demo-nginx-ecs"
}

resource "aws_ecr_repository" "ECRRepository2" {
    name = "demo-laravel-ecs"
}

resource "aws_vpc" "EC2VPC" {
    cidr_block = "172.16.0.0/24"
    enable_dns_support = true
    enable_dns_hostnames = true
    instance_tenancy = "default"
    tags = {
        Name = "ecs-larave-vpc"
    }
}

resource "aws_vpc_endpoint" "EC2VPCEndpoint" {
    vpc_endpoint_type = "Gateway"
    vpc_id = "${aws_vpc.EC2VPC.id}"
    service_name = "com.amazonaws.ap-northeast-1.s3"
    policy = "{\"Version\":\"2008-10-17\",\"Statement\":[{\"Effect\":\"Allow\",\"Principal\":\"*\",\"Action\":\"*\",\"Resource\":\"*\"}]}"
    private_dns_enabled = false
}

resource "aws_security_group" "EC2SecurityGroup" {
    description = "test-https"
    name = "test-https"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 443
        protocol = "tcp"
        to_port = 443
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_security_group" "EC2SecurityGroup2" {
    description = "test"
    name = "test-http"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 80
        protocol = "tcp"
        to_port = 80
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_security_group" "EC2SecurityGroup3" {
    description = "demo-laravel-ecs-alb-sg"
    name = "demo-laravel-ecs-alb-sg"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 80
        protocol = "tcp"
        to_port = 80
    }
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 443
        protocol = "tcp"
        to_port = 443
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_security_group" "EC2SecurityGroup4" {
    description = "2023-05-06T15:15:49.757Z"
    name = "ecs-la-7221"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 80
        protocol = "tcp"
        to_port = 80
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_security_group" "EC2SecurityGroup5" {
    description = "demo-laravel-ecs-web-sg"
    name = "demo-laravel-ecs-web-sg"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        security_groups = [
            "${aws_security_group.EC2SecurityGroup3.id}"
        ]
        from_port = 80
        protocol = "tcp"
        to_port = 80
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_security_group" "EC2SecurityGroup6" {
    description = "launch-wizard-1 created 2023-05-03T06:04:59.216Z"
    name = "ssh"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 22
        protocol = "tcp"
        to_port = 22
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_lb_target_group" "ElasticLoadBalancingV2TargetGroup" {
    health_check {
        interval = 30
        path = "/login"
        port = "traffic-port"
        protocol = "HTTP"
        timeout = 5
        unhealthy_threshold = 2
        healthy_threshold = 5
        matcher = "200"
    }
    port = 80
    protocol = "HTTP"
    target_type = "ip"
    vpc_id = "${aws_vpc.EC2VPC.id}"
    name = "demo-l-ecs-laravel-tg2"
}

resource "aws_lb_target_group" "ElasticLoadBalancingV2TargetGroup2" {
    health_check {
        interval = 300
        path = "/login"
        port = "traffic-port"
        protocol = "HTTP"
        timeout = 5
        unhealthy_threshold = 2
        healthy_threshold = 5
        matcher = "200"
    }
    port = 80
    protocol = "HTTP"
    target_type = "ip"
    vpc_id = "${aws_vpc.EC2VPC.id}"
    name = "demo-laravel-ecs-tg"
}

resource "aws_lb_target_group" "ElasticLoadBalancingV2TargetGroup3" {
    health_check {
        interval = 30
        path = "/login"
        port = "traffic-port"
        protocol = "HTTP"
        timeout = 5
        unhealthy_threshold = 2
        healthy_threshold = 5
        matcher = "200"
    }
    port = 80
    protocol = "HTTP"
    target_type = "ip"
    vpc_id = "${aws_vpc.EC2VPC.id}"
    name = "demo-laravel-ecs-tg-test"
}

resource "aws_route53_zone" "Route53HostedZone" {
    name = "laravel-sample.com."
}

resource "aws_route53_zone" "Route53HostedZone2" {
    name = "laraveltestmail.com."
}

resource "aws_route53_record" "Route53RecordSet" {
    name = "laravel-sample.com."
    type = "A"
    alias {
        name = "dualstack.demo-laravel-ecs-alb-73573798.ap-northeast-1.elb.amazonaws.com."
        zone_id = "Z14GRHDCWA56QT"
        evaluate_target_health = true
    }
    zone_id = "Z04915203PQF5PB1MWJUT"
}

resource "aws_route53_record" "Route53RecordSet2" {
    name = "laravel-sample.com."
    type = "NS"
    ttl = 172800
    records = [
        "ns-1462.awsdns-54.org.",
        "ns-376.awsdns-47.com.",
        "ns-2008.awsdns-59.co.uk.",
        "ns-723.awsdns-26.net."
    ]
    zone_id = "Z04915203PQF5PB1MWJUT"
}

resource "aws_route53_record" "Route53RecordSet3" {
    name = "laravel-sample.com."
    type = "SOA"
    ttl = 900
    records = [
        "ns-1462.awsdns-54.org. awsdns-hostmaster.amazon.com. 1 7200 900 1209600 86400"
    ]
    zone_id = "Z04915203PQF5PB1MWJUT"
}

resource "aws_route53_record" "Route53RecordSet4" {
    name = "laraveltestmail.com."
    type = "NS"
    ttl = 172800
    records = [
        "ns-1502.awsdns-59.org.",
        "ns-1007.awsdns-61.net.",
        "ns-1803.awsdns-33.co.uk.",
        "ns-355.awsdns-44.com."
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_route53_record" "Route53RecordSet5" {
    name = "laraveltestmail.com."
    type = "SOA"
    ttl = 900
    records = [
        "ns-1502.awsdns-59.org. awsdns-hostmaster.amazon.com. 1 7200 900 1209600 86400"
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_route53_record" "Route53RecordSet6" {
    name = "4gzznyg63njpr5w5tpybnlyqex5ghlwr._domainkey.laraveltestmail.com."
    type = "CNAME"
    ttl = 1800
    records = [
        "4gzznyg63njpr5w5tpybnlyqex5ghlwr.dkim.amazonses.com"
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_route53_record" "Route53RecordSet7" {
    name = "rljz23y4vuuycojdihliuzy4o6zgsxk5._domainkey.laraveltestmail.com."
    type = "CNAME"
    ttl = 1800
    records = [
        "rljz23y4vuuycojdihliuzy4o6zgsxk5.dkim.amazonses.com"
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_route53_record" "Route53RecordSet8" {
    name = "rtq4lqybcxickt6pl2nznmlfvpkqobte._domainkey.laraveltestmail.com."
    type = "CNAME"
    ttl = 1800
    records = [
        "rtq4lqybcxickt6pl2nznmlfvpkqobte.dkim.amazonses.com"
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_route53_record" "Route53RecordSet9" {
    name = "noreply.laraveltestmail.com."
    type = "MX"
    ttl = 300
    records = [
        "10 feedback-smtp.ap-northeast-1.amazonses.com"
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_route53_record" "Route53RecordSet10" {
    name = "noreply.laraveltestmail.com."
    type = "TXT"
    ttl = 300
    records = [
        "\"v=spf1 include:amazonses.com ~all\""
    ]
    zone_id = "Z09620911IQGKH9CMU59M"
}

resource "aws_ssm_parameter" "SSMParameter" {
    name = "/ecs/AWS_USE_PATH_STYLE_ENDPOINT"
    type = "String"
    value = "false"
}

resource "aws_ssm_parameter" "SSMParameter2" {
    name = "/ecs/LOG_CHANNEL"
    type = "String"
    value = "stack"
}

resource "aws_ssm_parameter" "SSMParameter3" {
    name = "/ecs/CACHE_DRIVER"
    type = "String"
    value = "file"
}

resource "aws_ssm_parameter" "SSMParameter4" {
    name = "/ecs/DB_DATABASE"
    type = "String"
    value = "deploy-laravel-db"
}

resource "aws_ssm_parameter" "SSMParameter5" {
    name = "/ecs/LOG_DEPRECATIONS_CHANNEL"
    type = "String"
    value = "null"
}

resource "aws_ssm_parameter" "SSMParameter6" {
    name = "/ecs/MAIL_MAILER"
    type = "String"
    value = "ses"
}

resource "aws_ssm_parameter" "SSMParameter7" {
    name = "/ecs/DB_PASSWORD"
    type = "String"
    value = "password"
}

resource "aws_ssm_parameter" "SSMParameter8" {
    name = "/ecs/REDIS_PASSWORD"
    type = "String"
    value = "null"
}

resource "aws_ssm_parameter" "SSMParameter9" {
    name = "/ecs/BROADCAST_DRIVER"
    type = "String"
    value = "log"
}

resource "aws_ssm_parameter" "SSMParameter10" {
    name = "/ecs/MIX_PUSHER_APP_CLUSTER"
    type = "String"
    value = "\"$${PUSHER_APP_CLUSTER}\""
}

resource "aws_ssm_parameter" "SSMParameter11" {
    name = "/ecs/DB_HOST"
    type = "String"
    value = "deploy-laravel-db.cu4jmgcqkesa.ap-northeast-1.rds.amazonaws.com"
}

resource "aws_ssm_parameter" "SSMParameter12" {
    name = "/ecs/MEMCACHED_HOST"
    type = "String"
    value = "127.0.0.1"
}

resource "aws_ssm_parameter" "SSMParameter13" {
    name = "/ecs/DB_CONNECTION"
    type = "String"
    value = "mysql"
}

resource "aws_ssm_parameter" "SSMParameter14" {
    name = "/ecs/LOG_LEVEL"
    type = "String"
    value = "debug"
}

resource "aws_ssm_parameter" "SSMParameter15" {
    name = "/ecs/MAIL_FROM_ADDRESS"
    type = "String"
    value = "noreply@laraveltestmail.com"
}

resource "aws_ssm_parameter" "SSMParameter16" {
    name = "/ecs/MAIL_FROM_NAME"
    type = "String"
    value = "\"laravel ToDo App\""
}

resource "aws_ssm_parameter" "SSMParameter17" {
    name = "/ecs/PUSHER_APP_CLUSTER"
    type = "String"
    value = "mt1"
}

resource "aws_ssm_parameter" "SSMParameter18" {
    name = "/ecs/QUEUE_CONNECTION"
    type = "String"
    value = "sync"
}

resource "aws_ssm_parameter" "SSMParameter19" {
    name = "/ecs/REDIS_HOST"
    type = "String"
    value = "127.0.0.1"
}

resource "aws_ssm_parameter" "SSMParameter20" {
    name = "/ecs/APP_ENV"
    type = "String"
    value = "production"
}

resource "aws_ssm_parameter" "SSMParameter21" {
    name = "/ecs/APP_DEBUG"
    type = "String"
    value = "true"
}

resource "aws_ssm_parameter" "SSMParameter22" {
    name = "/ecs/SESSION_DRIVER"
    type = "String"
    value = "file"
}

resource "aws_ssm_parameter" "SSMParameter23" {
    name = "/ecs/APP_KEY"
    type = "String"
    value = "base64:tToq+b5IMG0VB/IhfLc0vQGkIMspNFll3kX+5QNRZsw"
}

resource "aws_ssm_parameter" "SSMParameter24" {
    name = "/ecs/APP_NAME"
    type = "String"
    value = "Laravel"
}

resource "aws_ssm_parameter" "SSMParameter25" {
    name = "/ecs/AWS_DEFAULT_REGION"
    type = "String"
    value = "ap-northeast-1"
}

resource "aws_ssm_parameter" "SSMParameter26" {
    name = "/ecs/DB_PORT"
    type = "String"
    value = "3306"
}

resource "aws_ssm_parameter" "SSMParameter27" {
    name = "/ecs/FILESYSTEM_DISK"
    type = "String"
    value = "local"
}

resource "aws_ssm_parameter" "SSMParameter28" {
    name = "/ecs/MIX_PUSHER_APP_KEY"
    type = "String"
    value = "\"$${PUSHER_APP_KEY}\""
}

resource "aws_ssm_parameter" "SSMParameter29" {
    name = "/ecs/SESSION_LIFETIME"
    type = "String"
    value = "120"
}

resource "aws_ssm_parameter" "SSMParameter30" {
    name = "/ecs/APP_URL"
    type = "String"
    value = "http://localhost"
}

resource "aws_ssm_parameter" "SSMParameter31" {
    name = "/ecs/REDIS_PORT"
    type = "String"
    value = "6379"
}

resource "aws_ssm_parameter" "SSMParameter32" {
    name = "/ecs/DB_USERNAME"
    type = "String"
    value = "root"
}

resource "aws_iam_role" "IAMRole2" {
    path = "/"
    name = "ecsTaskExecutionRole"
    assume_role_policy = "{\"Version\":\"2012-10-17\",\"Statement\":[{\"Sid\":\"\",\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"ecs-tasks.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"}]}"
    max_session_duration = 3600
    tags = {}
}

resource "aws_iam_role" "IAMRole3" {
    path = "/"
    name = "_ECSRole"
    assume_role_policy = "{\"Version\":\"2012-10-17\",\"Statement\":[{\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"ecs-tasks.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"},{\"Effect\":\"Allow\",\"Principal\":{\"Federated\":\"arn:aws:iam::308690754229:oidc-provider/token.actions.githubusercontent.com\"},\"Action\":\"sts:AssumeRoleWithWebIdentity\",\"Condition\":{\"StringEquals\":{\"token.actions.githubusercontent.com:aud\":\"sts.amazonaws.com\"}}}]}"
    max_session_duration = 3600
    tags = {}
}

resource "aws_iam_role_policy" "IAMPolicy" {
    policy = <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "ecr:GetAuthorizationToken",
        "ecr:BatchCheckLayerAvailability",
        "ecr:GetDownloadUrlForLayer",
        "ecr:GetRepositoryPolicy",
        "ecr:DescribeRepositories",
        "ecr:ListImages",
        "ecr:DescribeImages",
        "ecr:BatchGetImage",
        "ecr:InitiateLayerUpload",
        "ecr:UploadLayerPart",
        "ecr:CompleteLayerUpload",
        "ecr:PutImage"
      ],
      "Resource": "*"
    }
  ]
}
EOF
    role = "${aws_iam_role.IAMRole3.name}"
}

resource "aws_iam_role_policy" "IAMPolicy2" {
    policy = <<EOF
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "ssm:GetParameters",
                "ssm:GetParametersByPath",
                "ssm:GetParameter"
            ],
            "Resource": "arn:aws:ssm:ap-northeast-1:123456789012:parameter/ecs/*"
        }
    ]
}
EOF
    role = "${aws_iam_role.IAMRole3.name}"
}

resource "aws_acm_certificate" "CertificateManagerCertificate" {
    domain_name = "laravel-sample.com"
    subject_alternative_names = [
        "laravel-sample.com"
    ]
    validation_method = "DNS"
}
